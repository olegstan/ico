<?php

add_filter( 'ot_theme_mode', '__return_true' );
require nk_admin()->admin_path . '/lib/OptionTree/ot-loader.php';

/**
 * Return Option from OptionTree
 */
if ( ! function_exists( 'yp_opts' ) ) :
function yp_opts($opt_name = null, $use_meta_box = null, $postId = null, $metabox_name = null){
    if($opt_name == null) {
        return null;
    }
    $value = null;

    // get post ID of shop page
    if (function_exists('is_shop') && is_shop()) {
        $postId = get_option( 'woocommerce_shop_page_id' );
    }

    // try get value from meta box
    if($use_meta_box) {
        if($postId == null) {
            $postId = get_the_ID();
        }
        $value = get_post_meta($postId, $metabox_name ? $metabox_name : $opt_name, true);
    }

    // get value from options
    if($value == null || $value == 'default') {
        $value = ot_get_option($opt_name, null);
    }

    // get std value
    if($value == null && function_exists( 'ot_settings_id' )) {
        $std = get_option( ot_settings_id(), array() );
        if(isset($std['settings'])) {
            $std = $std['settings'];

            foreach($std as $v) {
                if($v['id'] == $opt_name) {
                    $value = $v['std'];
                }
            }
        }
    }

    // change 'on' to 1 and 'off' to 0
    if($value == 'on') {
        $value = 1;
    } else if($value == 'off') {
        $value = 0;
    }

    return $value;
}
endif;
if ( ! function_exists( 'yp_opts_e' ) ) :
function yp_opts_e($opt_name = null, $use_meta_box = null, $postId = null){
    echo yp_opts($opt_name, $use_meta_box, $postId);
}
endif;

/**
 * Initialize the custom theme options.
 */
add_action( 'init', 'yp_custom_theme_options' );

// hide OptionTree button from admin menu
add_filter( 'ot_show_pages', '__return_false' );

/**
 * Build the custom settings & update OptionTree.
 */
if ( ! function_exists( 'yp_custom_theme_options' ) ) :
function yp_custom_theme_options() {

  /* OptionTree is not loaded yet, or this is not an admin request */
  if ( ! function_exists( 'ot_settings_id' ) || ! is_admin() )
    return false;

  /**
   * Get a copy of the saved settings array.
   */
  $saved_settings = get_option( ot_settings_id(), array() );


  $youplay_layouts = array(
    array(
      'value'       => 'cont',
      'label'       => 'Content',
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/cont.jpg'
    ),
    array(
      'value'       => 'cont-side',
      'label'       => 'Content + Sidebar',
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/cont-side.jpg'
    ),
    array(
      'value'       => 'side-cont',
      'label'       => 'Sidebar + Content',
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/side-cont.jpg'
    ),
    array(
      'value'       => 'banner-cont',
      'label'       => 'Banner + Content',
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/banner-cont.jpg'
    ),
    array(
      'value'       => 'banner-cont-side',
      'label'       => 'Banner + Content + Sidebar',
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/banner-cont-side.jpg'
    ),
    array(
      'value'       => 'banner-side-cont',
      'label'       => 'Banner + Sidebar + Content',
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/banner-side-cont.jpg'
    )
  );


  /**
   * Custom settings array that will eventually be
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array(
    'sections'        => array(
      array(
        'id'          => 'general',
        'title'       => '<i class="fa fa-cogs"></i> ' . esc_html__('General', 'youplay')
      ),
      array(
        'id'          => 'theme',
        'title'       => '<i class="fa fa-magic"></i> ' . esc_html__('Theme Style', 'youplay')
      ),
      array(
        'id'          => 'fonts',
        'title'       => '<i class="fa fa-font"></i> ' . esc_html__('Fonts', 'youplay')
      ),
      array(
        'id'          => 'navigation',
        'title'       => '<i class="fa fa-bars"></i> ' . esc_html__('Navigation', 'youplay')
      ),
      array(
        'id'          => 'single_page',
        'title'       => '<i class="fa fa-files-o"></i> ' . esc_html__('Single Page', 'youplay')
      ),
      array(
        'id'          => 'single_post',
        'title'       => '<i class="fa fa-thumb-tack"></i> ' . esc_html__('Single Post', 'youplay')
      ),
      array(
        'id'          => 'single_match',
        'title'       => '<i class="fa fa-trophy"></i> ' . esc_html__('Single Match', 'youplay')
      ),
      array(
        'id'          => 'archive',
        'title'       => '<i class="fa fa-archive"></i> ' . esc_html__('Posts Archive', 'youplay')
      ),
      array(
        'id'          => 'single_product',
        'title'       => '<i class="fa fa-shopping-cart"></i> ' . esc_html__('WooCommerce', 'youplay')
      ),
      array(
        'id'          => 'press',
        'title'       => '<i class="fa fa-forumbee"></i> ' . esc_html__('bbPress', 'youplay')
      ),
      array(
        'id'          => 'buddypress',
        'title'       => '<i class="fa fa-users"></i> ' . esc_html__('BuddyPress', 'youplay')
      ),
      array(
        'id'          => 'search',
        'title'       => '<i class="fa fa-search"></i> ' . esc_html__('Search Page', 'youplay')
      ),
      array(
        'id'          => '404',
        'title'       => '<i class="fa fa-exclamation-triangle"></i> ' . esc_html__('404', 'youplay')
      ),
      array(
        'id'          => 'footer',
        'title'       => '<i class="fa fa-hand-o-down"></i> ' . esc_html__('Footer', 'youplay')
      ),
      array(
        'id'          => 'twitter',
        'title'       => '<i class="fa fa-twitter"></i> ' . esc_html__('Twitter', 'youplay')
      ),
      array(
        'id'          => 'instagram',
        'title'       => '<i class="fa fa-instagram"></i> ' . esc_html__('Instagram', 'youplay')
      ),
      array(
        'id'          => 'maintenance',
        'title'       => '<i class="fa fa-wrench"></i> ' . esc_html__('Maintenance', 'youplay')
      ),
    ),
    'settings'        => array(


/**
------------------
GENERAL
------------------
*/
      array(
        'id'          => 'tab_general_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_custom_css',
        'label'       => esc_html__('Custom CSS', 'youplay'),
        'desc'        => esc_html__('Custom CSS for example: html {font-size:10px;}', 'youplay'),
        'std'         => '/* custom css */',
        'type'        => 'css',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_custom_js',
        'label'       => esc_html__('Custom JS', 'youplay'),
        'desc'        => esc_html__('Custom JS', 'youplay'),
        'std'         => '/* custom js */',
        'type'        => 'javascript',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_rtl',
        'label'       => esc_html__('RTL Mode', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_favicon',
        'label'       => esc_html__('Favicon', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/icon.png',
        'type'        => 'upload',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_preloader',
        'label'       => esc_html__('Show Preloader', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_preloader_logo',
        'label'       => esc_html__('Preloader Logo', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/logo-light.png',
        'type'        => 'upload',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'general_preloader:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_fade_between_pages',
        'label'       => esc_html__('Fade Between Pages', 'youplay'),
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'general_preloader:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_smoothscroll',
        'label'       => esc_html__('Smooth Scroll', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_parallax',
        'label'       => esc_html__('Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_boxed_content',
        'label'       => esc_html__('Boxed', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_boxed_content_size',
        'label'       => esc_html__('Boxed Width', 'youplay'),
        'desc'        => '',
        'std'         => '1400',
        'type'        => 'numeric-slider',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '1200,1600,10',
        'class'       => '',
        'condition'   => 'general_boxed_content:is(on)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'tab_general_background',
        'label'       => esc_html__('Background', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_background',
        'label'       => esc_html__('Enable Background', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_background_image',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/background.jpg',
        'type'        => 'upload',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'general_background:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_background_cover',
        'label'       => esc_html__('Cover', 'youplay'),
        'desc'        => esc_html__('Cover image if ON. Repeat image if OFF.', 'youplay'),
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'general_background:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_background_fixed',
        'label'       => esc_html__('Fixed', 'youplay'),
        'desc'        => esc_html__('Fixed attachment (not scroll with page)', 'youplay'),
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'general_background:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_background_parallax',
        'label'       => esc_html__('Parallax', 'youplay'),
        'desc'        => esc_html__('Change background position on scroll from top screen to bottom. Set 0 to disable', 'youplay'),
        'std'         => '500px',
        'type'        => 'text',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'general_background:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_content_bg_opacity',
        'label'       => esc_html__('Content background color opacity', 'youplay'),
        'desc'        => '',
        'std'         => '75',
        'type'        => 'numeric-slider',
        'section'     => 'general',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,100,1',
        'class'       => '',
        'condition'   => 'general_background:is(on)',
        'operator'    => 'and',
      ),




/**
------------------
THEME STYLE
------------------
*/
      array(
        'id'          => 'theme_style',
        'label'       => esc_html__('Style', 'youplay'),
        'desc'        => '',
        'std'         => 'dark',
        'type'        => 'select',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'dark',
            'label'       => esc_html__('Dark', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'shooter',
            'label'       => esc_html__('Shooter', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'anime',
            'label'       => esc_html__('Anime', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'light',
            'label'       => esc_html__('Light', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'custom',
            'label'       => esc_html__('Custom', 'youplay'),
            'src'         => ''
          )
        )
      ),

      array(
        'id'          => 'tab_theme_colors',
        'label'       => esc_html__('Colors', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'theme_colors_textblock',
        'label'       => esc_html__('If you want change colors - select Custom theme style.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:not(custom)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'theme_colors_from',
        'label'       => esc_html__('Scheme From', 'youplay'),
        'desc'        => esc_html__('When you change this, all custom options will be changed to selected scheme.', 'youplay'),
        'std'         => 'dark',
        'type'        => 'select',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'dark',
            'label'       => esc_html__('Dark', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'shooter',
            'label'       => esc_html__('Shooter', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'anime',
            'label'       => esc_html__('Anime', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'light',
            'label'       => esc_html__('Light', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'theme_main_color',
        'label'       => esc_html__('Main Color', 'youplay'),
        'desc'        => '',
        'std'         => '#D92B4C',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_back_color',
        'label'       => esc_html__('Back Color', 'youplay'),
        'desc'        => '',
        'std'         => '#160962',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_back_grey_color',
        'label'       => esc_html__('Back Grey Color', 'youplay'),
        'desc'        => '',
        'std'         => '#30303D',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_text_color',
        'label'       => esc_html__('Text Color', 'youplay'),
        'desc'        => '',
        'std'         => '#FFFFFF',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_primary_color',
        'label'       => esc_html__('Primary Color', 'youplay'),
        'desc'        => '',
        'std'         => '#2B6AD9',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_success_color',
        'label'       => esc_html__('Success Color', 'youplay'),
        'desc'        => '',
        'std'         => '#2BD964',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_info_color',
        'label'       => esc_html__('Info Color', 'youplay'),
        'desc'        => '',
        'std'         => '#2BD7D9',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_warning_color',
        'label'       => esc_html__('Warning Color', 'youplay'),
        'desc'        => '',
        'std'         => '#EB8324',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_danger_color',
        'label'       => esc_html__('Danger Color', 'youplay'),
        'desc'        => '',
        'std'         => '#D92B4C',
        'type'        => 'colorpicker',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),

      array(
        'id'          => 'tab_theme_sizes',
        'label'       => esc_html__('Sizes', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'theme_sizes_textblock',
        'label'       => esc_html__('If you want change sizes - select Custom theme style.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'theme_style:not(custom)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'theme_skew_size',
        'label'       => esc_html__('Skew Size', 'youplay'),
        'desc'        => esc_html__('All angled items (buttons, images, carousels, etc) uses this parameter.', 'youplay'),
        'std'         => '7',
        'type'        => 'numeric-slider',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '-10,10,1',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_navbar_height',
        'label'       => esc_html__('Navbar Height', 'youplay'),
        'desc'        => '',
        'std'         => '80',
        'type'        => 'numeric-slider',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '50,100,1',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_navbar_small_height',
        'label'       => esc_html__('Navbar Small Height', 'youplay'),
        'desc'        => '',
        'std'         => '50',
        'type'        => 'numeric-slider',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '30,100,1',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_banners_opacity',
        'label'       => esc_html__('Banners Image Opacity', 'youplay'),
        'desc'        => esc_html__('All banners background image opacity', 'youplay'),
        'std'         => '50',
        'type'        => 'numeric-slider',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,100,1',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_images_opacity',
        'label'       => esc_html__('Images Opacity', 'youplay'),
        'desc'        => esc_html__('All angled images backgrounds opacity', 'youplay'),
        'std'         => '50',
        'type'        => 'numeric-slider',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,100,1',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),
      array(
        'id'          => 'theme_images_hover_opacity',
        'label'       => esc_html__('Images Hover Opacity', 'youplay'),
        'desc'        => esc_html__('All angled images opacity on mouse over', 'youplay'),
        'std'         => '60',
        'type'        => 'numeric-slider',
        'section'     => 'theme',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,100,1',
        'class'       => '',
        'condition'   => 'theme_style:is(custom)',
        'operator'    => 'and',
      ),





/**
------------------
FONTS
------------------
*/
      array(
        'id'          => 'fonts_typography_body',
        'label'       => esc_html__('Body', 'youplay'),
        'desc'        => '',
        'std'         => array(
          'font-family'    => 'lato',
          'font-size'      => '14px',
          'letter-spacing' => '0.06em',
          'line-height'    => '20px'
        ),
        'type'        => 'typography',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'fonts_typography_banner_heading',
        'label'       => esc_html__('Banner Heading', 'youplay'),
        'desc'        => '',
        'std'         => array(
          'font-size'      => '50px',
          'line-height'    => '55px',
          'text-transform' => 'uppercase'
        ),
        'type'        => 'typography',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'fonts_typography_heading1',
        'label'       => esc_html__('Heading H1', 'youplay'),
        'desc'        => '',
        'std'         => array(
          'font-size'      => '50px',
          'line-height'    => '55px'
        ),
        'type'        => 'typography',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'fonts_typography_heading2',
        'label'       => esc_html__('Heading H2', 'youplay'),
        'desc'        => '',
        'std'         => array(
          'font-size'      => '30px',
          'line-height'    => '33px'
        ),
        'type'        => 'typography',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'fonts_typography_heading3',
        'label'       => esc_html__('Heading H3', 'youplay'),
        'desc'        => '',
        'std'         => array(
          'font-size'      => '24px',
          'line-height'    => '26px'
        ),
        'type'        => 'typography',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'fonts_typography_heading4',
        'label'       => esc_html__('Heading H4', 'youplay'),
        'desc'        => '',
        'std'         => array(
          'font-size'      => '18px',
          'line-height'    => '20px'
        ),
        'type'        => 'typography',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'fonts_typography_heading5',
        'label'       => esc_html__('Heading H5', 'youplay'),
        'desc'        => '',
        'std'         => array(
          'font-size'      => '14px',
          'line-height'    => '15px'
        ),
        'type'        => 'typography',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'fonts_typography_heading6',
        'label'       => esc_html__('Heading H6', 'youplay'),
        'desc'        => '',
        'std'         => array(
          'font-size'      => '12px',
          'line-height'    => '13px'
        ),
        'type'        => 'typography',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'google_fonts',
        'label'       => esc_html__('Google Font Load', 'youplay'),
        'desc'        => '',
        'std'         => array(
          array(
            'family'   => 'lato',
            'variants' => array('300', 'regular', '700')
          )
        ),
        'type'        => 'google-fonts',
        'section'     => 'fonts',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),



/**
------------------
NAVIGAION
------------------
*/
      array(
        'id'          => 'tab_navigation_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_show',
        'label'       => esc_html__('Show Navigation', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_small_size',
        'label'       => esc_html__('Small Navigation', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_show:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_logo',
        'label'       => esc_html__('Show Logo', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_show:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'general_logo',
        'label'       => esc_html__('Logo', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/logo-light.png',
        'type'        => 'upload',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_logo:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_logo_width',
        'label'       => esc_html__('Logo Width', 'youplay'),
        'desc'        => esc_html__('Logo width in pixels', 'youplay'),
        'std'         => '160',
        'type'        => 'numeric-slider',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '10,300,1',
        'class'       => '',
        'condition'   => 'navigation_logo:is(on)',
        'operator'    => 'and',
      ),
        array(
            'id'          => 'navigation_logo_small_width',
            'label'       => esc_html__('Small Logo Width', 'youplay'),
            'desc'        => esc_html__('Logo width in pixels', 'youplay'),
            'std'         => '110',
            'type'        => 'numeric-slider',
            'section'     => 'navigation',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '10,300,1',
            'class'       => '',
            'condition'   => 'navigation_logo:is(on)',
            'operator'    => 'and',
        ),
      array(
        'id'          => 'navigation_search',
        'label'       => esc_html__('Show Search', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_show:is(on)',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'tab_navigation_cart',
        'label'       => esc_html__('Shopping Cart', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_cart',
        'label'       => esc_html__('Show', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_show:is(on),navigation_show:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_cart_icon',
        'label'       => esc_html__('Show Icon', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_cart:is(on),navigation_show:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_cart_count',
        'label'       => esc_html__('Show Count', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_cart:is(on),navigation_show:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_cart_total',
        'label'       => esc_html__('Show Total', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_cart:is(on),navigation_show:is(on)',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'tab_navigation_login',
        'label'       => esc_html__('User Login', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_login',
        'label'       => esc_html__('Show', 'youplay'),
        'desc'        => sprintf(esc_html__('First of all you should install plugin - %s', 'youplay'), '<a href="https://wordpress.org/plugins/login-with-ajax/" target="_blank">https://wordpress.org/plugins/login-with-ajax/</a>'),
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_show:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_login_name',
        'label'       => esc_html__('Show Logged-in Username', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_login:is(on),navigation_show:is(on)',
        'operator'    => 'and'
      ),
        array(
            'id'          => 'navigation_login_registration_url',
            'label'       => esc_html__('Custom Registration URL (if empty - used popup)', 'youplay'),
            'desc'        => '',
            'std'         => '',
            'type'        => 'text',
            'section'     => 'navigation',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'navigation_login:is(on),navigation_show:is(on)',
            'operator'    => 'and'
        ),

      array(
        'id'          => 'tab_navigation_wpml',
        'label'       => esc_html__('WPML', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_wpml_language_selector',
        'label'       => esc_html__('Show Language Selector', 'youplay'),
        'desc'        => sprintf(esc_html__('First of all you should install plugin - %s', 'youplay'), '<a href="https://wpml.org/" target="_blank">https://wpml.org/</a>'),
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_show:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_wpml_country_flag',
        'label'       => esc_html__('Show Country Flag', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_wpml_language_selector:is(on),navigation_show:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'navigation_wpml_country_name',
        'label'       => esc_html__('Show Country Name', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'navigation',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'navigation_wpml_language_selector:is(on),navigation_show:is(on)',
        'operator'    => 'and'
      ),



/**
------------------
SINGLE PAGE
------------------
*/
      array(
        'id'          => 'tab_page_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_page_layout',
        'label'       => esc_html__('Layout', 'youplay'),
        'desc'        => '',
        'std'         => 'cont',
        'type'        => 'radio-image',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => $youplay_layouts
      ),
      array(
        'id'          => 'single_page_show_title',
        'label'       => esc_html__('Show Title', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_page_boxed_cont',
        'label'       => esc_html__('Boxed Content', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_page_comments',
        'label'       => esc_html__('Show Comments', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_page_banner',
        'label'       => esc_html__('Banner', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_page_banner',
        'label'       => esc_html__('Banner is not shown with selected Layout.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_page_layout:is(cont),single_page_layout:is(cont-side),single_page_layout:is(side-cont)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'single_page_banner_image',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/banner-blog-bg.jpg',
        'type'        => 'upload',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_page_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_page_banner_size',
        'label'       => esc_html__('Banner Size', 'youplay'),
        'desc'        => '',
        'std'         => 'xsmall',
        'type'        => 'select',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_page_layout:contains(banner)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'full',
            'label'       => esc_html__('Full', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'big',
            'label'       => esc_html__('Big', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'mid',
            'label'       => esc_html__('Mid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'small',
            'label'       => esc_html__('Small', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'xsmall',
            'label'       => esc_html__('Extra Small', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'single_page_banner_parallax',
        'label'       => esc_html__('Banner Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_page',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_page_layout:contains(banner)',
        'operator'    => 'and'
      ),



/**
------------------
SINGLE POST
------------------
*/
      array(
        'id'          => 'tab_post_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_layout',
        'label'       => esc_html__('Layout', 'youplay'),
        'desc'        => '',
        'std'         => 'banner-cont-side',
        'type'        => 'radio-image',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => $youplay_layouts
      ),
      array(
        'id'          => 'single_post_boxed_cont',
        'label'       => esc_html__('Boxed Content', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_comments',
        'label'       => esc_html__('Show Comments', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_post_banner',
        'label'       => esc_html__('Banner', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_post_banner',
        'label'       => esc_html__('Banner is not shown with selected Layout.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_post_layout:is(cont),single_post_layout:is(cont-side),single_post_layout:is(side-cont)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'single_post_banner_image',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/banner-blog-bg.jpg',
        'type'        => 'upload',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_post_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_banner_size',
        'label'       => esc_html__('Banner Size', 'youplay'),
        'desc'        => '',
        'std'         => 'xsmall',
        'type'        => 'select',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_post_layout:contains(banner)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'full',
            'label'       => esc_html__('Full', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'big',
            'label'       => esc_html__('Big', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'mid',
            'label'       => esc_html__('Mid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'small',
            'label'       => esc_html__('Small', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'xsmall',
            'label'       => esc_html__('Extra Small', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'single_post_banner_parallax',
        'label'       => esc_html__('Banner Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_post_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_post_noimage',
        'label'       => esc_html__('No Image', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_noimage',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/noimage.jpg',
        'type'        => 'upload',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_post_review',
        'label'       => esc_html__('Review', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_review',
        'label'       => esc_html__('Show Review', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_review_max_rating',
        'label'       => esc_html__('Max Rating', 'youplay'),
        'desc'        => '',
        'std'         => '10',
        'type'        => 'numeric-slider',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,50,1',
        'class'       => '',
        'condition'   => 'single_post_review:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_review_first_col',
        'label'       => esc_html__('First Column Title', 'youplay'),
        'desc'        => '',
        'std'         => '<h3 class="mt-0">Good</h3>',
        'type'        => 'textarea',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_post_review:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_review_first_list_before',
        'label'       => esc_html__('First Column Before Item', 'youplay'),
        'desc'        => '',
        'std'         => '<i class="fa fa-plus-circle"></i>',
        'type'        => 'text',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_post_review:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_review_second_col',
        'label'       => esc_html__('Second Column Title', 'youplay'),
        'desc'        => '',
        'std'         => '<h3 class="mt-0">Bad</h3>',
        'type'        => 'textarea',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_post_review:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_review_second_list_before',
        'label'       => esc_html__('Second Column Before Item', 'youplay'),
        'desc'        => '',
        'std'         => '<i class="fa fa-minus-circle"></i>',
        'type'        => 'text',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_post_review:is(on)',
        'operator'    => 'and'
      ),


      array(
        'id'          => 'tab_post_meta',
        'label'       => esc_html__('Meta', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_tags',
        'label'       => esc_html__('Show Tags', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_author',
        'label'       => esc_html__('Show Author', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_publish_date',
        'label'       => esc_html__('Show Publish Date', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_categories',
        'label'       => esc_html__('Show Categories', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_views',
        'label'       => esc_html__('Show Views', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_likes',
        'label'       => esc_html__('Show Likes', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_comments_count',
        'label'       => esc_html__('Show Comments Count', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_post_social_likes',
        'label'       => esc_html__('Social Likes', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_sharing_fb',
        'label'       => esc_html__('Facebook', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_sharing_tw',
        'label'       => esc_html__('Twitter', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_sharing_gp',
        'label'       => esc_html__('Google+', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_sharing_pin',
        'label'       => esc_html__('Pinterest', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_post_sharing_vk',
        'label'       => esc_html__('Vkontakte', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_post',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),



/**
------------------
SINGLE MATCH
------------------
*/
      array(
        'id'          => 'tab_match_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_layout',
        'label'       => esc_html__('Layout', 'youplay'),
        'desc'        => '',
        'std'         => 'banner-cont-side',
        'type'        => 'radio-image',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => $youplay_layouts
      ),
      array(
        'id'          => 'single_match_boxed_cont',
        'label'       => esc_html__('Boxed Content', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_comments',
        'label'       => esc_html__('Show Comments', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_match_banner',
        'label'       => esc_html__('Banner', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_post_banner',
        'label'       => esc_html__('Banner is not shown with selected Layout.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_match_layout:is(cont),single_match_layout:is(cont-side),single_match_layout:is(side-cont)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'single_match_banner_image',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/banner-blog-bg.jpg',
        'type'        => 'upload',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_match_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_banner_size',
        'label'       => esc_html__('Banner Size', 'youplay'),
        'desc'        => '',
        'std'         => 'mid',
        'type'        => 'select',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_match_layout:contains(banner)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'full',
            'label'       => esc_html__('Full', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'big',
            'label'       => esc_html__('Big', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'mid',
            'label'       => esc_html__('Mid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'small',
            'label'       => esc_html__('Small', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'xsmall',
            'label'       => esc_html__('Extra Small', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'single_page_banner_parallax',
        'label'       => esc_html__('Banner Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_match_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_match_noimage',
        'label'       => esc_html__('No Image', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_noimage',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/noimage.jpg',
        'type'        => 'upload',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),


      array(
        'id'          => 'tab_match_meta',
        'label'       => esc_html__('Meta', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_views',
        'label'       => esc_html__('Show Views', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_likes',
        'label'       => esc_html__('Show Likes', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_comments_count',
        'label'       => esc_html__('Show Comments Count', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_match_social_likes',
        'label'       => esc_html__('Social Likes', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_sharing_fb',
        'label'       => esc_html__('Facebook', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_sharing_tw',
        'label'       => esc_html__('Twitter', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_sharing_gp',
        'label'       => esc_html__('Google+', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_sharing_pin',
        'label'       => esc_html__('Pinterest', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_match_sharing_vk',
        'label'       => esc_html__('Vkontakte', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_match',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),



/**
------------------
ARCHIVE
------------------
*/
      array(
        'id'          => 'tab_archive_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'archive',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'archive_layout',
        'label'       => esc_html__('Layout', 'youplay'),
        'desc'        => '',
        'std'         => 'banner-cont-side',
        'type'        => 'radio-image',
        'section'     => 'archive',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => $youplay_layouts
      ),
      array(
        'id'          => 'archive_boxed_cont',
        'label'       => esc_html__('Boxed Content', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'archive',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_archive_banner',
        'label'       => esc_html__('Banner', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'archive',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_post_banner',
        'label'       => esc_html__('Banner is not shown with selected Layout.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'archive',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'archive_layout:is(cont),archive_layout:is(cont-side),archive_layout:is(side-cont)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'archive_banner_image',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/banner-blog-bg.jpg',
        'type'        => 'upload',
        'section'     => 'archive',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'archive_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'archive_banner_size',
        'label'       => esc_html__('Banner Size', 'youplay'),
        'desc'        => '',
        'std'         => 'xsmall',
        'type'        => 'select',
        'section'     => 'archive',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'archive_layout:contains(banner)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'full',
            'label'       => esc_html__('Full', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'big',
            'label'       => esc_html__('Big', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'mid',
            'label'       => esc_html__('Mid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'small',
            'label'       => esc_html__('Small', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'xsmall',
            'label'       => esc_html__('Extra Small', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'archive_banner_parallax',
        'label'       => esc_html__('Banner Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'archive',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'archive_layout:contains(banner)',
        'operator'    => 'and'
      ),



/**
------------------
WooCommerce
------------------
*/
      array(
        'id'          => 'tab_product_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_product_layout',
        'label'       => esc_html__('Layout', 'youplay'),
        'desc'        => '',
        'std'         => 'banner-cont-side',
        'type'        => 'radio-image',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => $youplay_layouts
      ),
      array(
        'id'          => 'single_product_boxed_cont',
        'label'       => esc_html__('Boxed Content', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_product_banner',
        'label'       => esc_html__('Banner', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_product_banner',
        'label'       => esc_html__('Banner is not shown with selected Layout.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_product_layout:is(cont),single_product_layout:is(cont-side),single_product_layout:is(side-cont)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'textblock_product_banner2',
        'label'       => '',
        'desc'        => esc_html__('Banner uses featured image. You can set it in product edit page.', 'youplay'),
        'std'         => '',
        'type'        => 'textblock',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_product_layout:contains(banner)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'single_product_banner_size',
        'label'       => esc_html__('Banner Size', 'youplay'),
        'desc'        => '',
        'std'         => 'mid',
        'type'        => 'select',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_product_layout:contains(banner)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'full',
            'label'       => esc_html__('Full', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'big',
            'label'       => esc_html__('Big', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'mid',
            'label'       => esc_html__('Mid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'small',
            'label'       => esc_html__('Small', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'xsmall',
            'label'       => esc_html__('Extra Small', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'single_product_banner_parallax',
        'label'       => esc_html__('Banner Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'single_product_layout:contains(banner)',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'tab_product_social_likes',
        'label'       => esc_html__('Social Likes', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_product_sharing_fb',
        'label'       => esc_html__('Facebook', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_product_sharing_tw',
        'label'       => esc_html__('Twitter', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_product_sharing_gp',
        'label'       => esc_html__('Google+', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_product_sharing_pin',
        'label'       => esc_html__('Pinterest', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'single_product_sharing_vk',
        'label'       => esc_html__('Vkontakte', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'tab_shop',
        'label'       => esc_html__('Shop Page', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_shop',
        'label'       => sprintf(
            esc_html__('This options will be applied to Shop page. %s', 'youplay'),
            '<a href="' . get_permalink( class_exists( 'WooCommerce' ) ? wc_get_page_id( 'shop' ) : '' ) . '">' . get_permalink( class_exists( 'WooCommerce' ) ? wc_get_page_id( 'shop' ) : '' ) . '</a>'
          ),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'shop_style',
        'label'       => esc_html__('Style', 'youplay'),
        'desc'        => '',
        'std'         => 'grid',
        'type'        => 'select',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'grid',
            'label'       => esc_html__('Grid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'row',
            'label'       => esc_html__('Row', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'shop_show_breadcrumbs',
        'label'       => esc_html__('Breadcrumbs', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'shop_show_result_count',
        'label'       => esc_html__('Result count text', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'shop_show_order_by',
        'label'       => esc_html__('"Order by" field', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'shop_show_ratings',
        'label'       => esc_html__('Ratings', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'shop_show_add_to_cart',
        'label'       => esc_html__('"Add to cart" buttons', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'single_product',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),



/**
------------------
bbPress
------------------
*/
      array(
        'id'          => 'tab_press_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'press_layout',
        'label'       => esc_html__('Layout', 'youplay'),
        'desc'        => '',
        'std'         => 'banner-cont-side',
        'type'        => 'radio-image',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => $youplay_layouts
      ),
      array(
        'id'          => 'press_breadcrumbs',
        'label'       => esc_html__('Breadcrumbs', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'press_boxed_cont',
        'label'       => esc_html__('Boxed Content', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_press_banner',
        'label'       => esc_html__('Banner', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_press_banner',
        'label'       => esc_html__('Banner is not shown with selected Layout.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'press_layout:is(cont),press_layout:is(cont-side),press_layout:is(side-cont)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'press_banner_image',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/banner-user-bg.jpg',
        'type'        => 'upload',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'press_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'press_banner_size',
        'label'       => esc_html__('Banner Size', 'youplay'),
        'desc'        => '',
        'std'         => 'small',
        'type'        => 'select',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'press_layout:contains(banner)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'full',
            'label'       => esc_html__('Full', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'big',
            'label'       => esc_html__('Big', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'mid',
            'label'       => esc_html__('Mid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'small',
            'label'       => esc_html__('Small', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'xsmall',
            'label'       => esc_html__('Extra Small', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'press_banner_parallax',
        'label'       => esc_html__('Banner Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'press',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'press_layout:contains(banner)',
        'operator'    => 'and'
      ),



/**
------------------
BUDYY PRESS
------------------
*/
      array(
        'id'          => 'tab_buddypress_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'buddypress_layout',
        'label'       => esc_html__('Layout', 'youplay'),
        'desc'        => '',
        'std'         => 'banner-cont-side',
        'type'        => 'radio-image',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => $youplay_layouts
      ),
      array(
        'id'          => 'buddypress_boxed_cont',
        'label'       => esc_html__('Boxed Content', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_buddypress_banner',
        'label'       => esc_html__('Banner', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_buddypress_banner',
        'label'       => esc_html__('Banner is not shown with selected Layout.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'buddypress_layout:is(cont),buddypress_layout:is(cont-side),buddypress_layout:is(side-cont)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'buddypress_banner_image',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/banner-blog-bg.jpg',
        'type'        => 'upload',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'buddypress_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'buddypress_banner_size',
        'label'       => esc_html__('Banner Size', 'youplay'),
        'desc'        => '',
        'std'         => 'small',
        'type'        => 'select',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'buddypress_layout:contains(banner)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'full',
            'label'       => esc_html__('Full', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'big',
            'label'       => esc_html__('Big', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'mid',
            'label'       => esc_html__('Mid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'small',
            'label'       => esc_html__('Small', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'xsmall',
            'label'       => esc_html__('Extra Small', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'buddypress_banner_parallax',
        'label'       => esc_html__('Banner Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'buddypress',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'buddypress_layout:contains(banner)',
        'operator'    => 'and'
      ),



/**
------------------
Search
------------------
*/
      array(
        'id'          => 'tab_search_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'search_page_layout',
        'label'       => esc_html__('Layout', 'youplay'),
        'desc'        => '',
        'std'         => 'banner-cont-side',
        'type'        => 'radio-image',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and',
        'choices'     => $youplay_layouts
      ),
      array(
        'id'          => 'search_page_boxed_cont',
        'label'       => esc_html__('Boxed Content', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_search_page_banner',
        'label'       => esc_html__('Banner', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'textblock_post_banner',
        'label'       => esc_html__('Banner is not shown with selected Layout.', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'search_page_layout:is(cont),search_page_layout:is(cont-side),search_page_layout:is(side-cont)',
        'operator'    => 'or'
      ),
      array(
        'id'          => 'search_page_banner_image',
        'label'       => esc_html__('Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/banner-blog-bg.jpg',
        'type'        => 'upload',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'search_page_layout:contains(banner)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'search_page_banner_size',
        'label'       => esc_html__('Banner Size', 'youplay'),
        'desc'        => '',
        'std'         => 'xsmall',
        'type'        => 'select',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'search_page_layout:contains(banner)',
        'operator'    => 'and',
        'choices'     => array(
          array(
            'value'       => 'full',
            'label'       => esc_html__('Full', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'big',
            'label'       => esc_html__('Big', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'mid',
            'label'       => esc_html__('Mid', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'small',
            'label'       => esc_html__('Small', 'youplay'),
            'src'         => ''
          ),
          array(
            'value'       => 'xsmall',
            'label'       => esc_html__('Extra Small', 'youplay'),
            'src'         => ''
          )
        )
      ),
      array(
        'id'          => 'search_page_banner_parallax',
        'label'       => esc_html__('Banner Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'search',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'search_page_layout:contains(banner)',
        'operator'    => 'and'
      ),


/**
------------------
404
------------------
*/
      array(
        'id'          => '404_title',
        'label'       => esc_html__('Page Title', 'youplay'),
        'desc'        => '',
        'std'         => esc_html__('404 - Page Not Found ;(', 'youplay'),
        'type'        => 'text',
        'section'     => '404',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => '404_content',
        'label'       => esc_html__('Content Text', 'youplay'),
        'desc'        => '',
        'std'         => '<h2>404</h2> <h3>Page Not Found ;(</h3>',
        'type'        => 'textarea',
        'section'     => '404',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => '404_search',
        'label'       => esc_html__('Show Search Form', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => '404',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => '404_background',
        'label'       => esc_html__('Background Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/404-bg.jpg',
        'type'        => 'upload',
        'section'     => '404',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),




/**
------------------
FOOTER
------------------
*/
      array(
        'id'          => 'tab_footer_main',
        'label'       => esc_html__('Main', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_text',
        'label'       => esc_html__('Text', 'youplay'),
        'desc'        => '',
        'std'         => '<div><strong>nK</strong> &copy; 2016. All rights reserved</div>',
        'type'        => 'textarea',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_parallax',
        'label'       => esc_html__('Parallax', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_show_background',
        'label'       => esc_html__('Show Background Image', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_background',
        'label'       => esc_html__('Background Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/footer-bg.jpg',
        'type'        => 'upload',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_show_background:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_footer_widgets',
        'label'       => esc_html__('Widgets', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_widgets',
        'label'       => esc_html__('Show', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_widget_1_width',
        'label'       => esc_html__('Widget 1 Width', 'youplay'),
        'desc'        => '',
        'std'         => '3',
        'type'        => 'numeric-slider',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,12,1',
        'class'       => '',
        'condition'   => 'footer_widgets:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_widget_2_width',
        'label'       => esc_html__('Widget 2 Width', 'youplay'),
        'desc'        => '',
        'std'         => '3',
        'type'        => 'numeric-slider',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,12,1',
        'class'       => '',
        'condition'   => 'footer_widgets:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_widget_3_width',
        'label'       => esc_html__('Widget 3 Width', 'youplay'),
        'desc'        => '',
        'std'         => '3',
        'type'        => 'numeric-slider',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,12,1',
        'class'       => '',
        'condition'   => 'footer_widgets:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_widget_4_width',
        'label'       => esc_html__('Widget 4 Width', 'youplay'),
        'desc'        => '',
        'std'         => '3',
        'type'        => 'numeric-slider',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '0,12,1',
        'class'       => '',
        'condition'   => 'footer_widgets:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'tab_footer_social',
        'label'       => esc_html__('Social', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'tab',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social',
        'label'       => esc_html__('Show', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_text',
        'label'       => esc_html__('Text', 'youplay'),
        'desc'        => '',
        'std'         => '<h3>Connect socially with <strong>youplay</strong></h3>',
        'type'        => 'textarea',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on)',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'footer_social_fb',
        'label'       => esc_html__('First Social Link', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_fb_icon',
        'label'       => '',
        'desc'        => 'Icon <a href="http://fontawesome.io/" taget="_blank">http://fontawesome.io/</a>',
        'std'         => 'fa fa-facebook-square',
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_fb:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_fb_label',
        'label'       => '',
        'desc'        => esc_html__('Label', 'youplay'),
        'std'         => esc_html__('Like on Facebook', 'youplay'),
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_fb:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_fb_url',
        'label'       => '',
        'desc'        => esc_html__('URL', 'youplay'),
        'std'         => 'https://www.facebook.com/people/Nk-Dev/100005706677229',
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_fb:is(on)',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'footer_social_tw',
        'label'       => esc_html__('Second Social Link', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_tw_icon',
        'label'       => '',
        'desc'        => 'Icon <a href="http://fontawesome.io/" taget="_blank">http://fontawesome.io/</a>',
        'std'         => 'fa fa-twitter-square',
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_tw:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_tw_label',
        'label'       => '',
        'desc'        => esc_html__('Label', 'youplay'),
        'std'         => esc_html__('Follow on Twitter', 'youplay'),
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_tw:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_tw_url',
        'label'       => '',
        'desc'        => esc_html__('URL', 'youplay'),
        'std'         => 'https://twitter.com/nkdevv',
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_tw:is(on)',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'footer_social_gp',
        'label'       => esc_html__('Third Social Link', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_gp_icon',
        'label'       => '',
        'desc'        => 'Icon <a href="http://fontawesome.io/" taget="_blank">http://fontawesome.io/</a>',
        'std'         => 'fa fa-google-plus-square',
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_gp:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_gp_label',
        'label'       => '',
        'desc'        => esc_html__('Label', 'youplay'),
        'std'         => esc_html__('Follow on Google+', 'youplay'),
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_gp:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_gp_url',
        'label'       => '',
        'desc'        => esc_html__('URL', 'youplay'),
        'std'         => 'https://plus.google.com/105540650896894558095/posts',
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_gp:is(on)',
        'operator'    => 'and'
      ),

      array(
        'id'          => 'footer_social_yt',
        'label'       => esc_html__('Fourth Social Link', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_yt_icon',
        'label'       => '',
        'desc'        => 'Icon <a href="http://fontawesome.io/" taget="_blank">http://fontawesome.io/</a>',
        'std'         => 'fa fa-youtube-square',
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_yt:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_yt_label',
        'label'       => '',
        'desc'        => esc_html__('Label', 'youplay'),
        'std'         => esc_html__('Watch on Youtube', 'youplay'),
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_yt:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'footer_social_yt_url',
        'label'       => '',
        'desc'        => esc_html__('URL', 'youplay'),
        'std'         => 'http://www.youtube.com/user/nKdevelopers',
        'type'        => 'text',
        'section'     => 'footer',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'footer_social:is(on),footer_social_yt:is(on)',
        'operator'    => 'and'
      ),

        array(
            'id'          => 'footer_social_fifth',
            'label'       => esc_html__('Fifth Social Link', 'youplay'),
            'desc'        => '',
            'std'         => 'off',
            'type'        => 'on-off',
            'section'     => 'footer',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'footer_social:is(on)',
            'operator'    => 'and'
        ),
        array(
            'id'          => 'footer_social_fifth_icon',
            'label'       => '',
            'desc'        => 'Icon <a href="http://fontawesome.io/" taget="_blank">http://fontawesome.io/</a>',
            'std'         => 'fa fa-twitch',
            'type'        => 'text',
            'section'     => 'footer',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'footer_social:is(on),footer_social_fifth:is(on)',
            'operator'    => 'and'
        ),
        array(
            'id'          => 'footer_social_fifth_label',
            'label'       => '',
            'desc'        => esc_html__('Label', 'youplay'),
            'std'         => esc_html__('Watch on Twitch', 'youplay'),
            'type'        => 'text',
            'section'     => 'footer',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'footer_social:is(on),footer_social_fifth:is(on)',
            'operator'    => 'and'
        ),
        array(
            'id'          => 'footer_social_fifth_url',
            'label'       => '',
            'desc'        => esc_html__('URL', 'youplay'),
            'std'         => 'https://twitch.tv/',
            'type'        => 'text',
            'section'     => 'footer',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'footer_social:is(on),footer_social_fifth:is(on)',
            'operator'    => 'and'
        ),

        array(
            'id'          => 'footer_social_sixth',
            'label'       => esc_html__('Sixth Social Link', 'youplay'),
            'desc'        => '',
            'std'         => 'off',
            'type'        => 'on-off',
            'section'     => 'footer',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'footer_social:is(on)',
            'operator'    => 'and'
        ),
        array(
            'id'          => 'footer_social_sixth_icon',
            'label'       => '',
            'desc'        => 'Icon <a href="http://fontawesome.io/" taget="_blank">http://fontawesome.io/</a>',
            'std'         => 'fa fa-steam',
            'type'        => 'text',
            'section'     => 'footer',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'footer_social:is(on),footer_social_sixth:is(on)',
            'operator'    => 'and'
        ),
        array(
            'id'          => 'footer_social_sixth_label',
            'label'       => '',
            'desc'        => esc_html__('Label', 'youplay'),
            'std'         => esc_html__('Subscribe on Steam', 'youplay'),
            'type'        => 'text',
            'section'     => 'footer',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'footer_social:is(on),footer_social_sixth:is(on)',
            'operator'    => 'and'
        ),
        array(
            'id'          => 'footer_social_sixth_url',
            'label'       => '',
            'desc'        => esc_html__('URL', 'youplay'),
            'std'         => 'http://store.steampowered.com/',
            'type'        => 'text',
            'section'     => 'footer',
            'rows'        => '',
            'post_type'   => '',
            'taxonomy'    => '',
            'min_max_step'=> '',
            'class'       => '',
            'condition'   => 'footer_social:is(on),footer_social_sixth:is(on)',
            'operator'    => 'and'
        ),




/**
------------------
Twitter
------------------
*/
      array(
        'id'          => 'twitter_textblock_howto',
        'label'       => sprintf(esc_html__('How to create Twitter API keys you can read here (or use google):  - %s', 'youplay'), '<a href="http://www.gabfirethemes.com/create-twitter-api-key/" target="_blank">http://www.gabfirethemes.com/create-twitter-api-key/</a>'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'twitter',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'twitter_consumer_key',
        'label'       => esc_html__('Consumer Key', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'twitter',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'twitter_consumer_secret',
        'label'       => esc_html__('Consumer Secret', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'twitter',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'twitter_access_token',
        'label'       => esc_html__('Access Token', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'twitter',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'twitter_access_token_secret',
        'label'       => esc_html__('Access Token Secret', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'twitter',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'twitter_cachetime',
        'label'       => esc_html__('Cache Time in seconds', 'youplay'),
        'desc'        => '',
        'std'         => 3600,
        'type'        => 'text',
        'section'     => 'twitter',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'twitter_show_replies',
        'label'       => esc_html__('Show Replies', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'twitter',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),




/**
------------------
Instagram
------------------
*/
      array(
        'id'          => 'instagram_textblock_howto',
        'label'       => sprintf(esc_html__('How to generate access token (or use google) - %s', 'youplay'), '<a href="http://instagram.pixelunion.net/" target="_blank">http://instagram.pixelunion.net/</a>') . '<br>' . sprintf(esc_html__('How to get user ID (or use google) - %s', 'youplay'), '<a href="http://jelled.com/instagram/lookup-user-id" target="_blank">http://jelled.com/instagram/lookup-user-id</a>'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'textblock_titled',
        'section'     => 'instagram',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'instagram_access_token',
        'label'       => esc_html__('Access Token', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'instagram',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'instagram_user_id',
        'label'       => esc_html__('User ID', 'youplay'),
        'desc'        => '',
        'std'         => '',
        'type'        => 'text',
        'section'     => 'instagram',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'instagram_cachetime',
        'label'       => esc_html__('Cache Time in seconds', 'youplay'),
        'desc'        => '',
        'std'         => 3600,
        'type'        => 'text',
        'section'     => 'instagram',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),



/**
------------------
MAINTENANCE
------------------
*/
      array(
        'id'          => 'maintenance',
        'label'       => esc_html__('Maintenance Mode', 'youplay'),
        'desc'        => '',
        'std'         => 'off',
        'type'        => 'on-off',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => '',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_except_admin',
        'label'       => esc_html__('Except Admin', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'maintenance:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_title',
        'label'       => esc_html__('Page Title', 'youplay'),
        'desc'        => '',
        'std'         => esc_html__('Maintenance', 'youplay'),
        'type'        => 'text',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'maintenance:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_background_image',
        'label'       => esc_html__('Background Image', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/maintenance-bg.jpg',
        'type'        => 'upload',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'maintenance:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_show_logo',
        'label'       => esc_html__('Show Logo', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'maintenance:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_logo',
        'label'       => esc_html__('Logo', 'youplay'),
        'desc'        => '',
        'std'         => get_template_directory_uri() . '/assets/images/logo-light.png',
        'type'        => 'upload',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'maintenance:is(on),maintenance_show_logo:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_show_countdown',
        'label'       => esc_html__('Show Countdown', 'youplay'),
        'desc'        => '',
        'std'         => 'on',
        'type'        => 'on-off',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'maintenance:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_countdown_date',
        'label'       => esc_html__('Countdown Date', 'youplay'),
        'desc'        => esc_html__('Date Format: YYYY-MM-DD hh:mm', 'youplay'),
        'std'         => '2017-01-21 12:00',
        'type'        => 'text',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'maintenance:is(on),maintenance_show_countdown:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_countdown_timezone',
        'label'       => esc_html__('Countdown Date Timezone', 'youplay'),
        'desc'        => '',
        'std'         => 'auto',
        'type'        => 'select',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'choices'     => youplay_get_tz_list(1),
        'condition'   => 'maintenance:is(on),maintenance_show_countdown:is(on)',
        'operator'    => 'and'
      ),
      array(
        'id'          => 'maintenance_text',
        'label'       => esc_html__('Text', 'youplay'),
        'desc'        => '',
        'std'         => '[yp_button href="#" size="lg"]Pre-Order[/yp_button]',
        'type'        => 'textarea',
        'section'     => 'maintenance',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'min_max_step'=> '',
        'class'       => '',
        'condition'   => 'maintenance:is(on)',
        'operator'    => 'and'
      ),
    )
  );

  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( ot_settings_id() . '_args', $custom_settings );

  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( ot_settings_id(), $custom_settings );
  }

  /* Lets OptionTree know the UI Builder is being overridden */
  global $ot_has_custom_theme_options;
  $ot_has_custom_theme_options = true;

}
endif;


// change typography fields
add_filter( 'ot_recognized_typography_fields', 'yp_options_typography', 10, 2 );
if ( ! function_exists( 'yp_options_typography' ) ) :
function yp_options_typography() {
  return array(
    'font-family',
    'font-size',
    'letter-spacing',
    'line-height',
    'text-transform'
  );
}
endif;



// compile main css file after save
add_filter( 'ot_after_theme_options_save', 'yp_compile_scss' );
if ( ! function_exists( 'yp_compile_scss' ) ) :
function yp_compile_scss($options) {
    if(yp_opts('theme_style') != 'custom' || !function_exists('nk_theme')) {
        return;
    }

    $theme_colors_from = yp_opts('theme_colors_from') == 'light' ? 'light' : 'dark';
    $theme_main_color = yp_opts('theme_main_color');
    $theme_back_color = yp_opts('theme_back_color');
    $theme_back_grey_color = yp_opts('theme_back_grey_color');
    $theme_text_color = yp_opts('theme_text_color');
    $theme_primary_color = yp_opts('theme_primary_color');
    $theme_success_color = yp_opts('theme_success_color');
    $theme_info_color = yp_opts('theme_info_color');
    $theme_warning_color = yp_opts('theme_warning_color');
    $theme_danger_color = yp_opts('theme_danger_color');
    $theme_skew_size = yp_opts('theme_skew_size');
    $theme_navbar_height = yp_opts('theme_navbar_height');
    $theme_navbar_small_height = yp_opts('theme_navbar_small_height');
    $theme_banners_opacity = yp_opts('theme_banners_opacity') / 100;
    $theme_images_opacity = yp_opts('theme_images_opacity') / 100;
    $theme_images_hover_opacity = yp_opts('theme_images_hover_opacity') / 100;

    $theme_data = wp_get_theme();
    $theme_parent = $theme_data->parent();
    if(!empty($theme_parent)) {
        $theme_data = $theme_parent;
    }
    $theme_version = $theme_data['Version'];

    $path = get_template_directory() . '/assets/scss/';
    $custom_vars = '
        @import "_helpers.scss";
        @import "_variables.scss";

        $theme_version:"' . $theme_version . '";

        $theme:' . $theme_colors_from . ';
        $main_color:' . $theme_main_color . ';
        $back_color:' . $theme_back_color . ';
        $back_darken_color:' . ($theme_colors_from == 'light' ? '#FFFFFF' : 'darken($back_color, 13)' ) . ';
        $back_grey_color:' . $theme_back_grey_color . ';
        $back_darken_grey_color: ' . ($theme_colors_from == 'light' ? 'lighten' : 'darken') . '($back_grey_color, 10);
        $text_color:' . $theme_text_color . ';
        $text_mute_color:  rgba($text_color, 0.5);
        $color_primary:' . $theme_primary_color . ';
        $color_success:' . $theme_success_color . ';
        $color_info:' . $theme_info_color . ';
        $color_warning:' . $theme_warning_color . ';
        $color_danger:' . $theme_danger_color . ';
        $skew_size:' . $theme_skew_size . 'deg;
        $banners_opacity:' . $theme_banners_opacity . ';
        $images_opacity:' . $theme_images_opacity . ';
        $images_hover_opacity:' . $theme_images_hover_opacity . ';
        $navbar-height:' . $theme_navbar_height . 'px;
        $navbar-sm-height:' . $theme_navbar_small_height . 'px;

        @import "_includes.scss"';

    nk_theme()->scss('youplay-custom.min.css', $path, $custom_vars);
}
endif;



// Add Revolution Slider select option
if ( ! function_exists( 'add_revslider_select_type' ) ) :
function add_revslider_select_type( $array ) {

  $array['revslider-select'] = 'Revolution Slider Select';
  return $array;

}
endif;
add_filter( 'ot_option_types_array', 'add_revslider_select_type' );

// Show RevolutionSlider select option
if ( ! function_exists( 'ot_type_revslider_select' ) ) :
function ot_type_revslider_select( $args = array() ) {
  extract( $args );
  $has_desc = $field_desc ? true : false;
  echo '<div class="format-setting type-revslider-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
  echo ($has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '');
    echo '<div class="format-setting-inner">';
    // Add This only if RevSlider is Activated
    if ( class_exists( 'RevSliderAdmin' ) ) {
      echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . $field_class . '">';

      /* get revolution array */
      $slider = new RevSlider();
      $arrSliders = $slider->getArrSliders();

      /* has slides */
      if ( ! empty( $arrSliders ) ) {
        echo '<option value="">-- ' . esc_html__( 'Choose One', 'youplay' ) . ' --</option>';
        foreach ( $arrSliders as $rev_slider ) {
          echo '<option value="' . esc_attr( $rev_slider->getParam('alias') ) . '"' . selected( $field_value, $rev_slider->getParam('alias'), false ) . '>' . esc_attr( $rev_slider->getParam('title') ) . '</option>';
        }
      } else {
        echo '<option value="">' . esc_html__( 'No Sliders Found', 'youplay' ) . '</option>';
      }
      echo '</select>';
    } else {
        echo '<span style="color: red;">' . esc_html__( 'Sorry! Revolution Slider is not Installed or Activated', 'youplay' ). '</span>';
    }
    echo '</div>';
  echo '</div>';
}
endif;




// change theme styles
//
if ( ! function_exists( 'ot_theme_style_selector' ) ) :
function ot_theme_style_selector() {
  wp_enqueue_script('nk-option-tree-style-selector', nk_admin()->admin_uri . '/assets/js/option-tree-style-selector.js', '', '', true);
}
endif;
add_filter( 'ot_admin_scripts_after', 'ot_theme_style_selector' );
