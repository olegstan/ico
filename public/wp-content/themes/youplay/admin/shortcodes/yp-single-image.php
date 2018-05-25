<?php
/**
 * YP Single Image
 *
 * Example:
 * [yp_single_image img_src="14" img_size="500x375" link_to_full_image="true" href="" target="_self" icon="fa fa-search-plus" center="false"]
 */
call_user_func('add_' . 'shortcode', 'yp_single_image', 'yp_single_image');
if ( ! function_exists( 'yp_single_image' ) ) :
function yp_single_image($atts, $content = null) {
    extract(shortcode_atts(array(
        "img_src"            => "",
        "img_size"           => "500x375",
        "link_to_full_image" => false,
        "href"               => "",
        "target"             => "_self",
        "icon"               => "fa fa-search-plus",
        "center"             => false,
        "class"              => ""
    ), $atts));

    $img = $img_full = $img_src;
    $icon = yp_check($icon) ? "<span class='" . yp_sanitize_class($icon) . " icon'></span>" : "";
    $max_width = '';
    $before = $after = '';

    if(is_numeric($img_src)) {
      $img = wp_get_attachment_image_src( $img_src, $img_size );
      $img = $img[0];
      $img_full = yp_check($link_to_full_image) ? wp_get_attachment_image_src( $img_src, "full" ) : array('');
      $img_full = $img_full[0];
      $max_width = "style='width: " . esc_attr($img[1]) . "px;'";
    }

    if($center) {
      $before = '<div class="align-center">';
      $after = '</div>';
    }

    if($link_to_full_image) {
      $href = $img_full;
      $target = '';
      $class .= ' image-popup';
    } else {
      $target = ' target="' . $target . '"';
    }

    return $before . "<a href='" . esc_url($href) . "' " . $target . " " . $max_width . " class='angled-img " . yp_sanitize_class($class) . "'>
              <div class='img'>
                <img src='" . esc_url($img) . "' alt=''>
              </div>
              $icon
            </a>" . $after;
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_yp_single_image" );
if ( ! function_exists( 'vc_yp_single_image' ) ) :
function vc_yp_single_image() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name" => esc_html__("nK Single Image", 'youplay'),
           "base" => "yp_single_image",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-single-image",
           "params" => array(
              array(
                 "type" => "attach_image",
                 "heading" => esc_html__("Image", 'youplay'),
                 "param_name" => "img_src",
                 "value" => "",
                 "description" => "",
                 "admin_label" => true,
              ),
              array(
                 "type" => "dropdown",
                 "heading" => esc_html__("Image Size", 'youplay'),
                 "param_name" => "img_size",
                 "value" => get_intermediate_image_sizes(),
                 "std" => "500x375",
                 "description" => "",
                 "admin_label" => true,
              ),
              array(
                 "type" => "iconpicker",
                 "heading" => esc_html__("Icon", 'youplay'),
                 "param_name" => "icon",
                 "value" => "fa fa-search-plus"
              ),
              array(
                  "type" => "checkbox",
                  "heading" => esc_html__( "Link to Full Image", 'youplay' ),
                  "param_name" => "link_to_full_image",
                  "value" => array( "" => true )
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Link", 'youplay'),
                 "param_name"  => "href",
                 "value"       => "",
                 "description" => '',
                'dependency' => array(
                  'element' => 'link_to_full_image',
                  'value_not_equal_to' => array(
                    "1"
                  ),
                ),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Target", 'youplay'),
                 "param_name"  => "target",
                 "value"       => "",
                 "description" => '',
                'dependency' => array(
                  'element' => 'link_to_full_image',
                  'value_not_equal_to' => array(
                    "1"
                  ),
                ),
              ),
              array(
                  "type" => "checkbox",
                  "heading" => esc_html__( "Center", 'youplay' ),
                  "param_name" => "center",
                  "value" => array( "" => true )
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Custom Classes", 'youplay'),
                 "param_name"  => "class",
                 "value"       => "",
                 "description" => "",
              ),
           )
        ) );
    }
}
endif;
