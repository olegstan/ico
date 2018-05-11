<?php
/**
 * nK Admin for nK Themes
 * http://themeforest.net/user/_nk/portfolio
 *
 * @package nK
 */
if(!class_exists('nK_Admin')):
class nK_Admin {
    /**
     * The single class instance.
     *
     * @since 1.0.0
     * @access private
     *
     * @var object
     */
    private static $_instance = null;

    /**
    * Main Instance
    * Ensures only one instance of this class exists in memory at any one time.
    *
    */
    public static function instance () {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            self::$_instance->init_globals();
            self::$_instance->init_includes();
            self::$_instance->init_actions();

            // init updater class to theme updates check
            self::$_instance->updater();
        }
        return self::$_instance;
    }

    private function __construct () {
        /* We do nothing here! */
    }

    /**
     * Init Global variables
     */
    private function init_globals () {
        $this->admin_path = get_template_directory() . '/admin';
        $this->admin_uri = get_template_directory_uri() . '/admin';

        // get theme data
        $theme_data = wp_get_theme();
        $theme_parent = $theme_data->parent();
        if(!empty($theme_parent)) {
            $theme_data = $theme_parent;
        }

        $this->theme_slug = $theme_data->get_stylesheet();
        $this->theme_name = $theme_data['Name'];
        $this->theme_version = $theme_data['Version'];
        $this->theme_uri = $theme_data->get('ThemeURI');
        $this->theme_is_child = !empty($theme_parent);

        $this->is_envato_hosted = defined('ENVATO_HOSTED_SITE');

        if ( ! $this->is_envato_hosted ) {
            $extra_headers = get_file_data(get_template_directory() . '/style.css', array(
                'Theme ID' => 'Theme ID'
            ), 'nk_theme');
            $this->theme_id = $extra_headers['Theme ID'];
        }
    }

    /**
     * Init Included Files
     */
    private function init_includes () {
        require $this->admin_path . '/theme-options.php';
        require $this->admin_path . '/theme-metaboxes.php';
        require $this->admin_path . '/shortcodes/_all.php';
        require $this->admin_path . '/widgets/_all.php';
        require $this->admin_path . '/menu/backend-walker.php';
        require $this->admin_path . '/menu/frontend-walker.php';

        if(is_admin()) {
            require $this->admin_path . '/plugins-activation.php';
        }

        require $this->admin_path . '/ajax-nk-demo-import.php';
        require $this->admin_path . '/ajax-nk-activation.php';

        require $this->admin_path . '/class-nk-admin-pages.php';
        require $this->admin_path . '/class-nk-activation.php';
        require $this->admin_path . '/class-nk-updater.php';

        // init dashboard
        if(is_admin()) {
            require $this->admin_path . '/admin-init.php';
        }
    }

    /**
     * Setup the hooks, actions and filters.
     */
    private function init_actions () {
        if (is_admin()) {
            add_action('admin_print_styles', array($this, 'admin_print_styles'));
        }
    }

    /**
     * Print Styles
     */
    public function admin_print_styles () {
        wp_enqueue_style('fontawesome', $this->admin_uri . '/assets/css/font-awesome.min.css');
        wp_enqueue_style('bootstrap-icons', $this->admin_uri . '/assets/css/bootstrap-icons.min.css');
        wp_enqueue_style('nk-admin-style', $this->admin_uri . '/assets/css/style.css');
        wp_enqueue_style('tether-drop', $this->admin_uri . '/assets/plugins/drop/dist/css/drop-theme-twipsy.min.css');

        wp_enqueue_script('tether', $this->admin_uri . '/assets/plugins/drop/dist/js/tether.min.js', '', '', true);
        wp_enqueue_script('tether-drop', $this->admin_uri . '/assets/plugins/drop/dist/js/drop.min.js', '', '', true);
        wp_enqueue_script('nk-admin-script', $this->admin_uri . '/assets/js/script.js', '', '', true);

        $dataInit = array(
            'demoImportConfirm' => esc_html__('This will import data from demo page. Clicking this option will replace your current theme options and widgets. Please, wait before the process end. It may take a while.', 'youplay'),
            'demoImportBeforeUnload' => esc_html__('The demo import process is not finished yet...', 'youplay'),
            'damoImportSuccess' => sprintf(
                esc_html__('Demo data successfully imported. Now, please install and run %s plugin once.', 'youplay'),
                sprintf('<a href="plugin-install.php?tab=plugin-information&plugin=regenerate-thumbnails&TB_iframe=true&width=750&height=600" class="thickbox">%s</a>', esc_html__('Regenerate Thumbnails', 'youplay'))
            ),
            'deactivateTheme' => esc_html__('This will deactivate your copy of theme. You will not be able to get direct theme updates.', 'youplay')
        );
        wp_localize_script('nk-admin-script', 'nkThemeAdminOptions', $dataInit);
    }

    /**
     * Options Manipulation
     */
    private function get_options () {
        $options_slug = 'nk_theme_' . $this->theme_name . '_options';
        return get_option($options_slug, array());
    }
    public function update_option ($name, $value) {
        $options_slug = 'nk_theme_' . $this->theme_name . '_options';
        $options = self::get_options();
        $options[self::sanitize_key($name)] = esc_html($value);
        update_option($options_slug, $options);
    }
    public function get_option ($name, $default = null) {
        $options = self::get_options();
        $name = self::sanitize_key($name);
        return isset($options[$name]) ? $options[$name] : $default;
    }

    /**
     * Sanitize data key
     */
    private function sanitize_key ($key) {
        return preg_replace( '/[^A-Za-z0-9\_]/i', '', str_replace( array( '-', ':' ), '_', $key ) );
    }

    /**
    * let_to_num function
    * This function transforms the php.ini notation for numbers (like '2M') to an integer
    */
    public function let_to_num( $size ) {
        $l   = substr( $size, -1 );
        $ret = substr( $size, 0, -1 );
        switch ( strtoupper( $l ) ) {
            case 'P': $ret *= 1024;
            case 'T': $ret *= 1024;
            case 'G': $ret *= 1024;
            case 'M': $ret *= 1024;
            case 'K': $ret *= 1024;
        }
        return $ret;
    }

    /**
     * All nK Classes
     */
    public function pages () {
        return nK_Admin_Pages::instance();
    }
    public function activation () {
        return nK_Activation::instance();
    }
    public function updater () {
        return nK_Updater::instance();
    }
}
endif;
if ( ! function_exists( 'nk_admin' ) ) :
function nk_admin() {
	return nK_Admin::instance();
}
endif;

nk_admin();
