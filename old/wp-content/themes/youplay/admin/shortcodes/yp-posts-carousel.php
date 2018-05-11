<?php
/**
 * YP Carousel
 *
 * Example:
 * [yp_posts_carousel style="1" post_type="ids" posts="1,2,3" exclude_ids="" custom_query="" taxonomies="" autoplay="" stage_padding="70" item_padding="0" show_price="true" show_rating="true" show_discount_badges="true" badges_always_show="false" boxed="false"]
 */
call_user_func('add_' . 'shortcode', 'yp_posts_carousel', 'yp_posts_carousel');
if ( ! function_exists( 'yp_posts_carousel' ) ) :
function yp_posts_carousel($atts, $content = null) {
    extract(shortcode_atts(array(
        "style"               => 1,
        "post_type"           => "ids",
        "count"               => 5,
        "taxonomies"          => "",
        "taxonomies_relation" => "OR",
        "posts"               => "",
        "custom_query"        => "",
        "exclude_ids"         => "",
        "orderby"             => "post_date",
        "order"               => "DESC",
        "autoplay"            => "",
        "loop"                => true,
        "stage_padding"       => 70,
        "item_padding"        => 0,
        "show_price"          => true,
        "show_rating"         => true,
        "show_discount_badges"=> true,
        "badges_always_show"  => false,
        "boxed"               => false,
        "class"               => ""
    ), $atts));



    /**
     * Set Up Query
     */
    $query_opts = array(
        'showposts' => intval($count),
        'posts_per_page' => intval($count),
        'order' => $order
    );

    // Order By
    switch ($orderby) {
        case 'title':
            $query_opts['orderby'] = 'title';
            break;

        case 'id':
            $query_opts['orderby'] = 'ID';
            break;

        case 'post__in':
            $query_opts['orderby'] = 'post__in';
            break;

        default:
            $query_opts['orderby'] = 'post_date';
            break;
    }

    // Exclude IDs
    $exclude_ids = explode(",", $exclude_ids);
    if ($exclude_ids) {
        $query_opts['post__not_in'] = $exclude_ids;
    }

    // IDs
    if ($post_type == 'ids') {
        $posts = explode(",", $posts);
        $query_opts['post_type'] = 'any';
        $query_opts['post__in'] = $posts;
    } // Custom Query
    else if ($post_type == 'custom_query') {
        $tmp_arr = array();
        parse_str(html_entity_decode($custom_query), $tmp_arr);
        $query_opts = array_merge($query_opts, $tmp_arr);
    } else {
        // Taxonomies
        $taxonomies = $taxonomies ? explode(",", $taxonomies) : array();
        if (!empty($taxonomies)) {
            $all_terms = yp_get_terms();
            $query_opts['tax_query'] = array(
                'relation' => yp_check($taxonomies_relation) ? $taxonomies_relation : 'OR'
            );
            foreach ($taxonomies as $taxonomy) {
                $taxonomy_name = null;

                foreach ($all_terms as $term) {
                    if ($term['value'] == $taxonomy) {
                        $taxonomy_name = $term['group'];
                        continue;
                    }
                }

                if ($taxonomy_name) {
                    $query_opts['tax_query'][] = array(
                        'taxonomy' => $taxonomy_name,
                        'field' => 'id',
                        'terms' => $taxonomy
                    );
                }
            }
        }
        $query_opts['post_type'] = $post_type;
    }


    /**
     * Work with printing posts
     */
    $before = '';
    $after = '';
    if(yp_check($boxed)) {
      $before = "<div class='container'>";
      $after = "</div>";
    }

    // autoplay
    $autoplay = intval($autoplay);
    if($autoplay) {
      $autoplay = 'data-autoplay="' . $autoplay . '"';
    } else {
      $autoplay = '';
    }

    $result_items = '';
    $yp_query = new WP_Query($query_opts);

    while ($yp_query->have_posts()) : $yp_query->the_post();
      global $product;

      $ID = get_the_ID();

      $title = "<h4>" . get_the_title() . "</h4>";
      $rating = '';
      $price = '';
      $badge = '';

      $img_ID = get_post_thumbnail_id( $ID );
      $img_src = wp_get_attachment_image_src( $img_ID, '500x375' );
      $img_src = $img_src[0];

      // use no-image
      if(!$img_src) {
        $img_src = yp_opts('single_post_noimage');
      }

      if ($product) {
        if(yp_check($show_rating)) {
          $rating = yp_get_rating( $product->get_average_rating() );
        }

        if(yp_check($show_discount_badges) && function_exists('yp_woo_discount_badge')) {
          $badge = yp_woo_discount_badge($product, yp_check($badges_always_show));
        }

        if(yp_check($show_price) && $price = $product->get_price_html()) {
          $price = '<div class="price">' . $price . '</div>';
        }
      }

      $item_content = '';
      if($style == 1) {
        $item_content =
          '<a class="angled-img" href="' . esc_url(get_permalink()) . '">
            <div class="img">
              <img src="' . esc_url($img_src) . '" alt="' . esc_attr(nk_get_img_alt($img_ID)) . '">
              ' . $badge . '
            </div>
            <div class="over-info">
              <div>
                <div>
                  ' . $title . '
                  ' . $rating . '
                  ' . $price . '
                </div>
              </div>
            </div>
          </a>';
      } else {
        $description = '';
        if( yp_check($price) && yp_check($rating) ) {
          $description =
            '<div class="row">
              <div class="col-xs-6">
                ' . $rating . '
              </div>
              <div class="col-xs-6">
                ' . $price . '
              </div>
            </div>';
        } else if( yp_check($price) ) {
          $description = $price;
        } else if( yp_check($rating) ) {
          $description = $rating;
        }

        $item_content =
          '<a class="angled-img" href="' . esc_url(get_permalink()) . '">
            <div class="img img-offset">
              <img src="' . esc_url($img_src) . '" alt="' . esc_attr(nk_get_img_alt($img_ID)) . '">
              ' . $badge . '
            </div>
            <div class="bottom-info">
              ' . $title . '
              ' . $description . '
            </div>
          </a>';
      }

      $result_items .= $item_content;

    endwhile;

    wp_reset_postdata();

    return $before . '<div class="youplay-carousel ' . yp_sanitize_class($class) . '" ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '" data-loop="' . esc_attr(yp_check($loop) ? 'true' : 'false') . '">' . $result_items . '</div>' . $after;
}
endif;


/* Add VC Shortcode */
add_action( "init", "vc_youplay_posts_carousel" );
if ( ! function_exists( 'vc_youplay_posts_carousel' ) ) :
function vc_youplay_posts_carousel() {
    if(function_exists("vc_map")) {

        $post_types = get_post_types( array() );
        $post_types_list = array();
        if ( is_array( $post_types ) && ! empty( $post_types ) ) {
          foreach ( $post_types as $post_type ) {
            if ( $post_type !== "revision" && $post_type !== "nav_menu_item"/* && $post_type !== "attachment"*/ ) {
              $label = ucfirst( $post_type );
              $post_types_list[] = array( $post_type, $label );
            }
          }
        }
        $post_types_list[] = array( "custom_query", esc_html__( "Custom Query", 'youplay' ) );
        $post_types_list[] = array( "ids", esc_html__( "List of IDs", 'youplay' ) );

        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Posts Carousel", 'youplay'),
           "base"     => "yp_posts_carousel",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-posts-carousel",
           "params"   => array(
               /**
                * General
                */
               array(
                   "type"       => "dropdown",
                   "heading"    => esc_html__("Style", 'youplay'),
                   "param_name" => "style",
                   "value"      => array(
                       esc_html__("Style 1", 'youplay') => 1,
                       esc_html__("Style 2", 'youplay') => 2
                   ),
                   "description" => ""
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Posts Count", 'youplay'),
                   "param_name"  => "count",
                   "value"       => 5,
                   "description" => "",
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Autoplay", 'youplay'),
                   "param_name"  => "autoplay",
                   "value"       => "",
                   "description" => esc_html__("Type integer value in ms", 'youplay')
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Loop", 'youplay'),
                   "param_name"  => "loop",
                   "std"         => true,
                   "value"       => array( "" => true ),
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Stage Padding", 'youplay'),
                   "param_name"  => "stage_padding",
                   "value"       => 70
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Item Padding", 'youplay'),
                   "param_name"  => "item_padding",
                   "value"       => 0
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Price", 'youplay'),
                   "param_name"  => "show_price",
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Rating", 'youplay'),
                   "param_name"  => "show_rating",
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Discount Badges", 'youplay'),
                   "param_name"  => "show_discount_badges",
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Badges Always Show", 'youplay'),
                   "param_name"  => "badges_always_show",
                   "value"       => array( "" => true ),
                   "description" => esc_html__("When unchecked - show only on mouse over", 'youplay'),
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Boxed", 'youplay'),
                   "param_name"  => "boxed",
                   "value"       => array( "" => true ),
                   "description" => esc_html__("Use it when your page content boxed disabled", 'youplay'),
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Custom Classes", 'youplay'),
                   "param_name"  => "class",
                   "value"       => "",
                   "description" => "",
               ),


               /**
                * Query
                */
               array(
                   "type"        => "dropdown",
                   "heading"     => esc_html__( "Data source", 'youplay' ),
                   "group"       => esc_html__("Query", 'youplay'),
                   "param_name"  => "post_type",
                   "value"       => $post_types_list,
                   "std"         => "ids",
                   "description" => esc_html__( "Select content type", 'youplay' )
               ),
               array(
                   'type' => 'autocomplete',
                   'heading' => esc_html__( 'Narrow data source', 'youplay' ),
                   "group"       => esc_html__("Query", 'youplay'),
                   'param_name' => 'taxonomies',
                   'settings' => array(
                       'multiple' => true,
                       'min_length' => 1,
                       'groups' => true,
                       // In UI show results grouped by groups, default false
                       'unique_values' => true,
                       // In UI show results except selected. NB! You should manually check values in backend, default false
                       'display_inline' => true,
                       // In UI show results inline view, default false (each value in own line)
                       'delay' => 100,
                       // delay for search. default 500
                       'auto_focus' => true,
                       // auto focus input, default true
                       'values' => yp_get_terms()
                   ),
                   'description' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'youplay' ),
                   'dependency' => array(
                       'element' => 'post_type',
                       'value_not_equal_to' => array(
                           'ids',
                           'custom_query',
                       ),
                   ),
               ),
               array(
                   "type" => "dropdown",
                   "heading" => esc_html__("Data source relation", 'youplay'),
                   "group" => esc_html__("Query", 'youplay'),
                   "param_name" => "taxonomies_relation",
                   "value" => array(
                       "OR", "AND"
                   ),
                   "std" => "OR",
                   'dependency' => array(
                       'element' => 'post_type',
                       'value_not_equal_to' => array(
                           'ids',
                           'custom_query',
                       ),
                   ),
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("IDs", 'youplay'),
                   "group"       => esc_html__("Query", 'youplay'),
                   "param_name"  => "posts",
                   "value"       => "",
                   "description" => esc_html__("Type here the posts, pages, etc. IDs you want to use separated by coma. ex: 23,24,25", 'youplay'),
                   "dependency"  => array(
                       "element"   => "post_type",
                       "value"     => array( "ids" ),
                   ),
               ),
               array(
                   "type"        => "textarea_safe",
                   "heading"     => esc_html__( "Custom Query", 'youplay' ),
                   "group"       => esc_html__("Query", 'youplay'),
                   "param_name"  => "custom_query",
                   "description" => sprintf(
                       esc_html__( "Build custom query according to %s.", 'youplay' ),
                       "<a href='http://codex.wordpress.org/Function_Reference/query_posts'>WordPress Codex</a>"
                   ),
                   "dependency"  => array(
                       "element"   => "post_type",
                       "value"     => array( "custom_query" ),
                   ),
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__( "Exclude IDs", 'youplay' ),
                   "group"       => esc_html__("Query", 'youplay'),
                   "param_name"  => "exclude_ids",
                   "description" => esc_html__( "Type here the posts, pages, etc. IDs you want to use separated by coma. ex: 23,24,25", 'youplay' ),
               ),
               array(
                   "type" => "dropdown",
                   "heading" => esc_html__("Order By", 'youplay'),
                   "group" => esc_html__("Query", 'youplay'),
                   "param_name" => "orderby",
                   "value" => array(
                       esc_html__("Date", 'youplay') => 'post_date',
                       esc_html__("Title", 'youplay') => 'title',
                       esc_html__("ID", 'youplay') => 'id',
                       esc_html__("Post In", 'youplay') => 'post__in',
                   ),
                   "std" => "post_date",
               ),
               array(
                   "type" => "dropdown",
                   "heading" => esc_html__("Order", 'youplay'),
                   "group" => esc_html__("Query", 'youplay'),
                   "param_name" => "order",
                   "value" => array(
                       "DESC", "ASC"
                   ),
                   "std" => "DESC",
               ),
           )
        ) );
    }
}
endif;
