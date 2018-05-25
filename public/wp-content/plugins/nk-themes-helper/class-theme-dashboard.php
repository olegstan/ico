<?php
/**
 * Theme Dashboard
 */
if (!class_exists('nK_Helper_Theme_Dashboard')) :
    class nK_Helper_Theme_Dashboard {
        /**
         * The single class instance.
         */
        private static $_instance = null;

        /**
         * Main Instance
         * Ensures only one instance of this class exists in memory at any one time.
         */
        public static function instance($options = array()) {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
                self::$_instance->init_globals();
                self::$_instance->init_includes();
                self::$_instance->init_actions();
                self::$_instance->set_options($options);
                self::$_instance->setup();
            }
            return self::$_instance;
        }

        private function __construct() {
            /* We do nothing here! */
        }

        public $options = array();
        public $is_envato_hosted;
        public $theme_id;
        public $theme_slug;
        public $theme_name;
        public $theme_version;
        public $theme_uri;
        public $theme_is_child;

        /**
         * Init global variables
         */
        public function init_globals () {
            $theme_data = wp_get_theme();
            $theme_parent = $theme_data->parent();
            if(!empty($theme_parent)) {
                $theme_data = $theme_parent;
            }

            $this->theme_slug = $theme_data->get_stylesheet();
            $this->theme_name = $theme_data->get('Name');
            $this->theme_version = $theme_data->get('Version');
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
        public function init_includes () {
            require nk_theme()->plugin_path . '/theme-dashboard/ajax-demo-import.php';
            require nk_theme()->plugin_path . '/theme-dashboard/ajax-activation.php';

            require nk_theme()->plugin_path . '/theme-dashboard/class-admin-pages.php';
            require nk_theme()->plugin_path . '/theme-dashboard/class-activation.php';
            require nk_theme()->plugin_path . '/theme-dashboard/class-updater.php';
        }

        /**
         * Setup the hooks, actions and filters.
         */
        public function init_actions () {
            if (is_admin()) {
                add_action('admin_print_styles', array($this, 'admin_print_styles'));
            }
        }

        /**
         * Print Styles
         */
        public function admin_print_styles () {
            wp_enqueue_style('fontawesome', nk_theme()->plugin_url . 'assets/plugins/fontawesome/css/font-awesome.min.css');
            wp_enqueue_style('tether-drop', nk_theme()->plugin_url . 'assets/plugins/drop/dist/css/drop-theme-twipsy.min.css');
            wp_enqueue_style('nk-theme-dashboard', nk_theme()->plugin_url . 'assets/css/theme-dashboard.css');

            wp_enqueue_script('tether', nk_theme()->plugin_url . 'assets/plugins/drop/dist/js/tether.min.js', '', '', true);
            wp_enqueue_script('tether-drop', nk_theme()->plugin_url . 'assets/plugins/drop/dist/js/drop.min.js', '', '', true);
            wp_enqueue_script('nk-theme-dashboard', nk_theme()->plugin_url . 'assets/js/theme-dashboard.js', '', '', true);

            $dataInit = array(
                'demoImportConfirm' => esc_html__('This will import data from demo page. Clicking this option will replace your current theme options and widgets. Please, wait before the process end. It may take a while.', 'nk-themes-helper'),
                'demoImportBeforeUnload' => esc_html__('The demo import process is not finished yet...', 'nk-themes-helper'),
                'damoImportSuccess' => sprintf(
                    esc_html__('Demo data successfully imported. Now, please install and run %s plugin once.', 'nk-themes-helper'),
                    sprintf('<a href="plugin-install.php?tab=plugin-information&plugin=regenerate-thumbnails&TB_iframe=true&width=750&height=600" class="thickbox">%s</a>', esc_html__('Regenerate Thumbnails', 'nk-themes-helper'))
                ),
                'deactivateTheme' => esc_html__('This will deactivate your copy of theme. You will not be able to get direct theme updates.', 'nk-themes-helper')
            );
            wp_localize_script('nk-theme-dashboard', 'nkThemeDashboardOptions', $dataInit);
        }

        /**
         * Set options
         *
         * @param array $options
         */
        public function set_options ($options = array()) {
            $default = array(
                'top_message'       => esc_html__('%s is now installed and ready to use! Get ready to build something beautiful. Read below for additional information. We hope you enjoy it!', 'nk-themes-helper'),
                'top_button_text'   => esc_html__('%s on ThemeForest', 'nk-themes-helper'),
                'top_button_url'    => $this->theme_uri,
                'foot_message'      => esc_html__('Thank you for choosing %s.', 'nk-themes-helper'),
                'min_requirements'   => array(
                    'php_version' => '5.4.0',
                    'memory_limit' => '128M',
                    'max_execution_time' => 60
                ),
                'demos'    => array(
                    // Example:
                    //
                    // 'main' => array(
                    //     'title'      => esc_html__('Main', 'nk-themes-helper'),
                    //     'preview'    => 'https://wp.nkdev.info/khaki/corporate/',
                    //     'thumbnail'  => get_template_directory_uri() . '/admin/assets/images/demos/main.png'
                    //     'demo_data'  => array(
                    //         'woocommerce_options' => array(
                    //             'shop_page_title' => 'Our Store',
                    //             'cart_page_title' => 'Cart',
                    //             'checkout_page_title' => 'Checkout',
                    //             'myaccount_page_title' => 'My Account',
                    //         ),
                    //         'navigations' => array(
                    //             'Top Menu Icons' => 'top_icons_menu'
                    //         ),
                    //         'demo_data_file' => $theme_demo_path . '/main/content.xml',
                    //         'widgets_file' => $theme_demo_path . '/main/widgets.json',
                    //         'customizer_file' => $theme_demo_path . '/main/customizer.dat',
                    //     )
                    // ),
                ),
                'pages'    => array(
                    'nk-theme' => array(
                        'title' => esc_html__('Dashboard', 'nk-themes-helper'),
                        'template' => 'dashboard.php'
                    ),
                    'nk-theme-plugins' => array(
                        'title'    => esc_html__('Plugins', 'nk-themes-helper'),
                        'template' => 'plugins.php'
                    ),
                    'nk-theme-demos' => array(
                        'title'    => esc_html__('Demo Import', 'nk-themes-helper'),
                        'template' => 'demos.php'
                    ),
                    'customize.php' => array(
                        'external_uri' => true,
                        'title' => esc_html__('Customize', 'nk-themes-helper')
                    ),
                ),
                'edd_name' => ''
            );

            /* Hide dashboard page if Envato Hosted */
            if ( $this->is_envato_hosted ) {
                $default['pages']['nk-theme'] = $default['pages']['nk-theme-plugins'];
                unset($default['pages']['nk-theme-plugins']);
            }

            $this->options = array_merge($default, $options);
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
        private function sanitize_key ($key) {
            return preg_replace( '/[^A-Za-z0-9\_]/i', '', str_replace( array( '-', ':' ), '_', $key ) );
        }

        /**
         * Classes
         */
        public function pages () {
            return nK_Helper_Theme_Dashboard_Pages::instance();
        }
        public function activation () {
            return nK_Helper_Theme_Dashboard_Activation::instance();
        }
        public function updater () {
            return nK_Helper_Theme_Dashboard_Updater::instance();
        }

        /**
         * Init dashboard code
         */
        public function setup () {
            // init classes
            $this->updater();
            $this->pages();
            $this->activation();
        }
    }
endif;
