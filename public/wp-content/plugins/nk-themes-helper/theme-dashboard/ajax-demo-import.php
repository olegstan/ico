<?php

// Demo Importer AJAX Action
add_action('wp_ajax_nk_theme_demo_import_action', 'nk_theme_demo_import_action');
function nk_theme_demo_import_action () {
    // reset info about current demo
    nk_theme()->theme_dashboard()->update_option('active_demo', '');

    $demo_options = nk_theme()->theme_dashboard()->options;

    $demo_name = 'main';
    if (isset($_GET['demo_name']) && trim($_GET['demo_name']) != '') {
        $demo_name = $_GET['demo_name'];
    }

    // get demo data
    if (!isset($demo_options) || !isset($demo_options['demos']) || !count($demo_options['demos']) || !isset($demo_options['demos'][$demo_name])) {
        exit();
    }
    $demo_data = $demo_options['demos'][$demo_name]['demo_data'];

    // start import
    nk_theme()->demo_importer()->stream_import($demo_data);

    // save info about current active demo
    nk_theme()->theme_dashboard()->update_option('active_demo', $demo_name);

    exit();
}
