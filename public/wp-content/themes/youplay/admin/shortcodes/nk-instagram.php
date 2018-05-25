<?php
// Required plugin nK Themes Helper
if(!function_exists('nk_theme')) {
    return;
}

/**
 * nK Instagram
 *
 * Example:
 * [nk_instagram count="6"]
 */
call_user_func('add_' . 'shortcode', 'nk_instagram', 'nk_instagram');
if ( ! function_exists( 'nk_instagram' ) ) :
function nk_instagram($atts, $content = null) {
    extract(shortcode_atts(array(
        "count" => 6,
        "class" => ''
    ), $atts));

    $result = '';

    // Get the images from Instagram.
    nk_theme()->instagram()->set_data(array(
        'access_token' => yp_opts('instagram_access_token'),
        'user_id'      => yp_opts('instagram_user_id'),
        'cachetime'    => yp_opts('instagram_cachetime')
    ));
    $instagram = nk_theme()->instagram()->get_instagram($count);

    if (!nk_theme()->instagram()->has_error() && !empty($instagram)) {
        $result .= '<div class="youplay-instagram row small-gap">';
        for ($i = 0; $i < $count; $i++) {
            $item = $instagram[$i];
            $result .= '<div class="col-xs-4">
                            <a href="' . esc_attr($item->link) . '" target="_blank">
                                <img src="' . esc_attr($item->images->thumbnail->url) . '" alt="">
                            </a>
                        </div>';
        }
        $result .= '</div>';
    } else if(nk_theme()->instagram()->has_error()) {
        $result = nk_theme()->instagram()->get_error()->message;
    }

    return $result;
}
endif;


/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_nk_instagram" );
if ( ! function_exists( 'vc_nk_instagram' ) ) :
function vc_nk_instagram() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name" => esc_html__("nK Instagram", 'youplay'),
           "base" => "nk_instagram",
           "controls" => "full",
           "category" => "nK",
           "icon" => "icon-nk icon-nk-instagram",
           "params" => array(
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Count", 'youplay'),
                 "param_name"  => "count",
                 "value"       => 6,
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
