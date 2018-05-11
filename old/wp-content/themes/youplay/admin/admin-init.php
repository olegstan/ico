<?php

/**
 * redirect to theme page after activation
 */
global $pagenow;
if ('themes.php' == $pagenow && isset($_GET['activated'])) {
    wp_redirect(admin_url('admin.php?page=nk-theme'));
}

/**
 * Init Admin Theme Pages
 */
nk_admin()->pages()->init(array(
    'top_message'       => sprintf(esc_html__('%s is now installed and ready to use! Get ready to build something beautiful. Read below for additional information. We hope you enjoy it!', 'youplay'), nk_admin()->theme_name),
    'top_button_text'   => sprintf(esc_html__('%s on ThemeForest', 'youplay'), nk_admin()->theme_name),
    'top_button_url'    => 'https://wp.nkdev.info/_api/?item_id=' . nk_admin()->theme_id . '&type=redirect',
    'top_tweet_text'    => sprintf(esc_html__('%s - the most atmospheric gaming theme for #WordPress by @nkdevv', 'youplay'), nk_admin()->theme_name),
    'top_tweet_url'     => 'https://wp.nkdev.info/_api/?item_id=' . nk_admin()->theme_id . '&type=redirect',
    'top_tweet_via'     => 'nkdevv',
    'foot_message'      => sprintf(esc_html__('Thank you for choosing %s.', 'youplay'), nk_admin()->theme_name),
    'min_requirements'   => array(
        'php_version'         => '5.3.0',
        'memory_limit'        => '256M',
        'max_execution_time'  => 180
    ),
    'demos'             => array(
        'dark' => array(
            'title'      => esc_html__('Dark', 'youplay'),
            'preview'    => 'https://wp.nkdev.info/youplay/main/',
            'thumbnail'  => get_template_directory_uri() . '/admin/assets/images/demos/dark.jpg'
        ),
        'shooter' => array(
            'title'      => esc_html__('Shooter', 'youplay'),
            'preview' => 'https://wp.nkdev.info/youplay/demos/shooter/',
            'thumbnail'  => get_template_directory_uri() . '/admin/assets/images/demos/shooter.jpg'
        ),
        'anime' => array(
            'title'      => esc_html__('Anime', 'youplay'),
            'preview' => 'https://wp.nkdev.info/youplay/demos/anime/',
            'thumbnail'  => get_template_directory_uri() . '/admin/assets/images/demos/anime.jpg'
        ),
        'light' => array(
            'title'      => esc_html__('Light', 'youplay'),
            'preview' => 'https://wp.nkdev.info/youplay/demos/light/',
            'thumbnail'  => get_template_directory_uri() . '/admin/assets/images/demos/light.jpg'
        )
    )
));

$yp_dashboard_pages = array();
if ( ! nk_admin()->is_envato_hosted ) {
    $yp_dashboard_pages = array(
        'nk-theme' => array(
            'title' => esc_html__('Dashboard', 'youplay'),
            'template' => 'dashboard.php'
        ),
        'nk-theme-plugins' => array(
            'title'    => esc_html__('Plugins', 'youplay'),
            'template' => 'plugins.php'
        ),
    );
} else {
    $yp_dashboard_pages = array(
        'nk-theme' => array(
            'title'    => esc_html__('Plugins', 'youplay'),
            'template' => 'plugins.php'
        ),
    );
}
$yp_dashboard_pages = array_merge($yp_dashboard_pages, array(
    'nk-theme-demos' => array(
        'title'    => esc_html__('Demo Import', 'youplay'),
        'template' => 'demos.php'
    ),
    'ot-theme-options' => array(
        'title' => esc_html__('Options', 'youplay')
    ),
));

nk_admin()->pages()->add_pages($yp_dashboard_pages);