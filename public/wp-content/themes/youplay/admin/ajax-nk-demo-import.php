<?php
// Demo Importer AJAX Action
add_action('wp_ajax_nk_demo_import_action', 'nk_demo_import_action');
function nk_demo_import_action () {
    if(!function_exists('nk_theme') || !method_exists(nk_theme()->demo_importer(), 'stream_import')) {
        exit;
    }

    $demo_name = 'dark';
    if (isset($_GET['demo_name']) && trim($_GET['demo_name']) != '') {
        $demo_name = $_GET['demo_name'];
    }

    $demo_path = nk_admin()->admin_path . '/demos/' . $demo_name;

    $import_data = array(
        'blog_options' => array(
            'permalink' => '/%postname%/',
            'page_on_front_title' => 'Main',
            'posts_per_page' => 6
        ),
        'woocommerce_options' => array(
            'shop_page_title' => 'Shop',
            'cart_page_title' => 'Basket',
            'checkout_page_title' => 'Checkout',
            'myaccount_page_title' => 'My Account',
            'shop_catalog_image_size' => array(
                'width'  => 500,
                'height' => 375,
                'crop'   => 1
            ),
            'shop_single_image_size' => array(
                'width'  => 1600,
                'height' => 1000,
                'crop'   => 0
            ),
            'shop_thumbnail_image_size' => array(
                'width'  => 180,
                'height' => 135,
                'crop'   => 1
            )
        ),
        'navigations' => array(
            'Main Menu' => 'primary',
            'Right Menu' => 'primary-right',
        ),
        'demo_data_file' => $demo_path . '/content.xml',
        'widgets_file' => $demo_path . '/widgets.json',
        'customizer_file' => $demo_path . '/customizer.dat',
        'rev_slider_file' => $demo_path . '/godlike-carousel1.zip',
    );

    if($demo_name === 'dark') {
        $demo_name['rev_slider_file'] = $demo_path . '/products_slider.zip';
    }

    nk_theme()->demo_importer()->stream_import($import_data);

    // options tree importer
    nk_theme()->demo_importer()->import_demo_options_tree($demo_path . '/theme_options.txt');

    // save info about current active demo
    nk_admin()->update_option('active_demo', $demo_name);

    die();
}
