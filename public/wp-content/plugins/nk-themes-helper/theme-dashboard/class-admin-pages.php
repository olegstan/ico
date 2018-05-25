<?php
/**
 * Admin Class for _nK themes
 */
if (!class_exists('nK_Helper_Theme_Dashboard_Pages')) :
class nK_Helper_Theme_Dashboard_Pages {
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
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            self::$_instance->init_globals();
        }
        return self::$_instance;
    }

    private function __construct() {
        /* We do nothing here! */
    }

    /**
     * Init Global variables
     */
    private function init_globals() {
        add_action( 'admin_menu', array($this, 'admin_menu') );
        add_action( 'admin_init', array($this, 'admin_init' ));
    }

    // admin pages
    protected $pages;

    // admin pages data
    public $pages_data;

   // admin init action
   public function admin_init() {
       if ( isset( $_GET['nk-deactivate'] ) && 'deactivate-plugin' == $_GET['nk-deactivate'] ) {
           check_admin_referer( 'nk-deactivate', 'nk-deactivate-nonce' );

           $plugins = TGM_Plugin_Activation::$instance->plugins;

           foreach ( $plugins as $plugin ) {
               if ( $plugin['slug'] == $_GET['plugin'] ) {
                   deactivate_plugins( $plugin['file_path'] );
               }
           }
       }
       if ( isset( $_GET['nk-activate'] ) && 'activate-plugin' == $_GET['nk-activate'] ) {
           check_admin_referer( 'nk-activate', 'nk-activate-nonce' );

           $plugins = TGM_Plugin_Activation::$instance->plugins;

           foreach ( $plugins as $plugin ) {
               if ( isset( $_GET['plugin'] ) && $plugin['slug'] == $_GET['plugin'] ) {
                   activate_plugin( $plugin['file_path'] );

                   wp_redirect( admin_url( 'admin.php?page=nk-theme-plugins' ) );
                   exit;
               }
           }
       }
   }

   // admin menus
   public function admin_menu() {
       if(!is_array(nk_theme()->theme_dashboard()->options['pages'])) {
           return;
       }

       $main_item_title = nk_theme()->theme_dashboard()->theme_name;
       if(nk_theme()->theme_dashboard()->updater()->is_update_available()) {
            $main_item_title .= ' <span class="awaiting-mod"><span>New</span></span>';
       }

       global $menu, $submenu, $admin_page_hooks, $_registered_pages, $_parent_pages;

       $parent_slug = 'nk-theme';

       // add top menu
       $admin_page_hooks[$parent_slug] = sanitize_title( $main_item_title );
       $hookname = get_plugin_page_hookname($parent_slug, '');
       if (!empty( $hookname ) && current_user_can( 'edit_theme_options' )) {
           add_action( $hookname, array($this, 'print_pages') );
       }
       $menu['3.22222'] = array($main_item_title, 'edit_theme_options', $parent_slug, $main_item_title, 'menu-top ' . $hookname, $hookname, set_url_scheme('dashicons-admin-nk'));
       $_registered_pages[$hookname] = true;
       $_parent_pages[$parent_slug] = false;

       // add submenus
       foreach( nk_theme()->theme_dashboard()->options['pages'] as $name => $page ) {
           if(isset($page['external_uri'])) {
               $submenu[$parent_slug][] = array($page['title'], 'edit_theme_options', get_admin_url(null, $name));
           } else {
               $submenu[$parent_slug][] = array($page['title'], 'edit_theme_options', $name, $page['title']);
               $hookname = get_plugin_page_hookname($name, $parent_slug);
               if (!empty ($hookname)) {
                   add_action($hookname, array($this, 'print_pages'));
               }
               $_registered_pages[$hookname] = true;
               $_parent_pages[$name] = $parent_slug;
           }
       }
   }
   public function admin_bar_menu() {
       if(!is_array(nk_theme()->theme_dashboard()->options['pages'])) {
           return;
       }
       global $wp_admin_bar;

       $wp_admin_bar->add_menu(array('id' => 'nk-theme-top', 'title' => '<i class="icon-nk"></i>' . nk_admin()->theme_name, 'href' => menu_page_url('nk-theme', 0)));
       foreach( nk_theme()->theme_dashboard()->options['pages'] as $name => $page ) {
           $uri = isset($page['external_uri']) ? get_admin_url(null, $name) : menu_page_url($name, 0);
           $wp_admin_bar->add_menu(array('parent' => 'nk-theme-top', 'title' => $page['title'], 'id' => $name . '_top', 'href' => $uri));
       }
   }

   // print pages
   public function print_pages() {
       ?>
       <div class="wrap about-wrap nk-theme-wrap">

           <h1><?php echo sprintf(esc_html__('Welcome to %s', 'nk-themes-helper'), nk_theme()->theme_dashboard()->theme_name . ' <span class="nk-theme-version">v ' . nk_theme()->theme_dashboard()->theme_version . '</span>') ?></h1>

           <div class="about-text">
               <?php echo sprintf(nk_theme()->theme_dashboard()->options['top_message'], nk_theme()->theme_dashboard()->theme_name); ?>
           </div>
           <div class="about-text">
               <a href="<?php echo esc_url(nk_theme()->theme_dashboard()->options['top_button_url']); ?>" class="button button-primary" target="_blank"><?php echo sprintf(nk_theme()->theme_dashboard()->options['top_button_text'], nk_theme()->theme_dashboard()->theme_name); ?></a>
           </div>

           <?php
           $tab = 'nk-theme';
           if ( isset($_GET['page']) ) {
               $tab = $_GET['page'];
           }
           echo '<div id="icon-themes" class="icon32"><br></div>';
           echo '<h2 class="nav-tab-wrapper">';
           foreach( nk_theme()->theme_dashboard()->options['pages'] as $name => $page ) {
               $class = ( $name == $tab ) ? ' nav-tab-active' : '';
               $uri = isset($page['external_uri']) ? get_admin_url(null, $name) : menu_page_url($name, 0);
               echo "<a class='nav-tab" . $class . "' href='" . $uri . "'>" . $page['title'] . "</a>";
           }
           echo '</h2>';
           ?>

           <div id="poststuff">
               <?php
               if (isset(nk_theme()->theme_dashboard()->options['pages'][$tab]) && isset(nk_theme()->theme_dashboard()->options['pages'][$tab]['template'])) {
                   require nk_theme()->plugin_path .'/theme-dashboard/admin_pages/' . nk_theme()->theme_dashboard()->options['pages'][$tab]['template'];
               }
               ?>
               <p class="description">
                   <?php echo sprintf(nk_theme()->theme_dashboard()->options['foot_message'], nk_theme()->theme_dashboard()->theme_name); ?>
               </p>
           </div>

       </div>
       <?php
   }
}
endif;
