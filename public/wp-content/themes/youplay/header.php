<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Youplay
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php if ( yp_opts('general_favicon') ): ?>
        <link rel="shortcut icon" href="<?php echo esc_url(yp_opts('general_favicon')); ?>" />
    <?php endif; ?>

    <?php wp_head(); ?>
</head>

<?php
$parallax_background = '';
if(yp_opts('general_background', true) && yp_opts('general_background_parallax', true)) {
    $parallax_scroll = yp_opts('general_background_parallax', true);
    $parallax_background = 'data-start="background-position: 50% ' . esc_attr($parallax_scroll) . ';" data-end="background-position: 50% 0px;"';
}
?>

<body <?php body_class(); ?> <?php echo $parallax_background; ?>>


    <?php if ( yp_opts('general_preloader') ): ?>
        <!-- Preloader -->
        <div class="page-preloader preloader-wrapp">
            <?php if ( yp_opts('general_preloader_logo') ): ?>
                <img src="<?php echo esc_url(yp_opts('general_preloader_logo')); ?>" alt="<?php echo esc_attr(nk_get_img_alt(yp_opts('general_preloader_logo'))); ?>">
            <?php endif; ?>
            <div class="preloader"></div>
        </div>
        <!-- /Preloader -->
    <?php endif; ?>

    <?php if ( yp_opts('navigation_show') ): ?>
        <!-- Navbar -->
        <nav class="navbar-youplay navbar navbar-default navbar-fixed-top <?php echo yp_opts('navigation_small_size') ? 'navbar-small' : ''; ?>">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="off-canvas" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <?php if ( yp_opts('general_logo') && yp_opts('navigation_logo') ): ?>
                        <a class="navbar-brand" href="<?php echo esc_url(home_url()); ?>">
                            <img src="<?php echo esc_url(yp_opts('general_logo')); ?>" alt="<?php echo esc_attr(nk_get_img_alt(yp_opts('general_logo'))); ?>">
                        </a>
                    <?php endif; ?>
                </div>

                <div id="navbar" class="navbar-collapse collapse">
                    <?php wp_nav_menu(array(
                        'theme_location'  => 'primary',
                        'container'       => '',
                        'menu_class'      => 'nav navbar-nav',
                        'walker'          => new nk_walker()
                    ) ); ?>

                    <?php
                    /**
                     * WPML language selector
                     */
                    if(!function_exists('youplay_print_wpml_menu_item')):
                    function youplay_print_wpml_menu_item ($lang, $has_dropdown = false) {
                        ?>
                        <a <?php echo $has_dropdown ? 'class="dropdown-toggle" data-toggle="dropdown"' : ''; ?> href="<?php echo $lang['url']; ?>" role="button" aria-expanded="false">
                            <?php if (yp_opts('navigation_wpml_country_flag')) : ?>
                                <img src="<?php echo $lang['country_flag_url']; ?>" height="12" width="18" alt="<?php echo $lang['native_name']; ?>">
                            <?php endif; ?>
                            <?php if (yp_opts('navigation_wpml_country_name')) : ?>
                                <span><?php echo $lang['native_name']; ?></span>
                            <?php endif; ?>
                        </a>
                        <?php
                    }
                    endif;
                    if(yp_opts('navigation_wpml_language_selector') && function_exists('icl_object_id') && function_exists('icl_get_languages')):
                        $languages = icl_get_languages();
                        $has_dropdown = count($languages) > 1;
                        ?>
                        <?php if(count($languages) > 0): ?>
                            <ul class="nav navbar-nav navbar-right navbar-wpml">
                                <li class="menu-item menu-item-wpml <?php echo $has_dropdown ? 'dropdown dropdown-hover' : ''; ?>">
                                    <?php youplay_print_wpml_menu_item($languages[ICL_LANGUAGE_CODE], true); ?>

                                    <?php if($has_dropdown): ?>
                                        <div class="dropdown-menu"><ul role="menu">

                                        <?php foreach($languages as $l): ?>
                                            <li class="menu-item flagmenu">
                                                <?php youplay_print_wpml_menu_item($l); ?>
                                            </li>
                                        <?php endforeach; ?>

                                        </ul></div>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php
                    /**
                     * WooCommerce cart
                     */
                    if(yp_opts('navigation_cart') && class_exists('woocommerce') && function_exists('woocommerce_mini_cart')):
                        $cart_contents_count = WC()->cart->get_cart_contents_count();
                        $show_count = $cart_contents_count > 0 ? '' : 'style="display: none;"';
                    ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown dropdown-hover dropdown-cart">
                                <a href="<?php echo esc_url(WC()->cart->get_cart_url()); ?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <?php if(yp_opts('navigation_cart_icon')): ?>
                                        <i class="fa fa-shopping-cart"></i>
                                    <?php endif; ?>

                                    <?php if(yp_opts('navigation_cart_count')): ?>
                                        <span class="nav_products_count badge bg-default mnl-10" <?php echo $show_count; ?>>
                                            <?php echo intval($cart_contents_count); ?>
                                        </span>
                                        &zwnj;
                                    <?php endif; ?>

                                    <?php if(yp_opts('navigation_cart_total')): ?>
                                        <span class="nav_products_subtotal ml-5">
                                            <?php echo WC()->cart->get_cart_subtotal(); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                                <div class="dropdown-menu pb-20" style="width: 300px;">
                                    <div class="widget_shopping_cart_content">
                                        <?php woocommerce_mini_cart(); ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    <?php endif; ?>

                    <?php
                    /**
                     * Search Toggle
                     */
                    if(yp_opts('navigation_search')) : ?>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="search-toggle"><a href="javascript:void(0)" role="button" aria-expanded="false"><span class="fa fa-search"></span></a></li>
                        </ul>
                    <?php endif; ?>

                    <?php
                    /**
                     * AJAX Login
                     */
                    if ( function_exists( 'login_with_ajax' ) && yp_opts('navigation_login') ) : ?>
                        <?php
                            $username = '';

                            if(yp_opts('navigation_login_name')) {
                                $username = wp_get_current_user();
                                $username = $username->data ? $username->display_name : '';
                            }
                        ?>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown dropdown-hover dropdown-user">
                                <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-user"></i>
									<?php if (is_user_logged_in()){ echo "Credits: "; echo spin2spin_jackpot_plugin_show_balance(); echo '<span style="text-transform: none;"> mBTC</span>'; echo "<br>";}?> 
                                    <?php echo esc_html($username); ?>
                                    <?php 
                                    // show buddypress notifications count
                                    if(function_exists('bp_notifications_get_unread_notification_count') && is_user_logged_in()) {
                                        $notifications = bp_notifications_get_unread_notification_count(bp_loggedin_user_id());
                                        if($notifications) {
                                            echo ' <span class="badge bg-default">' . $notifications . '</span>';
                                        }
                                    }
                                    ?>
                                    <span class="caret"></span>
                                </a>
                                <div class="dropdown-menu pb-20" style="width: 300px;">
                                    <div class="block-content m-20 mnb-10 mt-0">
                                        <?php
                                        login_with_ajax(array(
                                            "profile_link" => 1
                                        ));
                                        ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    <?php endif; ?>

                    <?php wp_nav_menu(array(
                        'theme_location'  => 'primary-right',
                        'container'       => '',
                        'menu_class'      => 'nav navbar-nav navbar-right',
                        'walker'          => new nk_walker()
                    ) ); ?>
                </div>
            </div>
        </nav>
        <!-- /Navbar -->
    <?php endif; ?>
