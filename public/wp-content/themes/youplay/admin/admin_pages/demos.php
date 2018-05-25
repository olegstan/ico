<?php

$demos = nk_admin()->pages()->pages_data['demos'];
add_thickbox();

if(!function_exists('nk_theme')) {
    ?>
    <p class="about-description">
        <mark class="error">
            <?php esc_html_e( 'You should install and activate required plugin nK Themes Helper. Find it in "Plugins" tab.', 'youplay' ); ?>
        </mark>
    </p>
    <?php
    return;
}

?>

<div class="about-description">
    <?php esc_html_e('Important Notes:', 'youplay'); ?>
    <ol>
        <li>
            <?php
            echo sprintf(
                esc_html__('We recommend import demo on a clean WordPress website. To reset your installation use %s.', 'youplay'),
                '<a href="plugin-install.php?tab=plugin-information&amp;plugin=wordpress-reset&amp;TB_iframe=true&amp;width=750&amp;height=600" class="thickbox">' . esc_html__('Reset WordPress Plugin', 'youplay') . '</a>'
            );
            ?>
        </li>
        <li><?php esc_html_e('All recommended and required plugin should be installed.', 'youplay'); ?></li>
        <li>
            <?php
            echo sprintf(
                esc_html__('After demo data imported, run %s plugin.', 'youplay'),
                '<a href="plugin-install.php?tab=plugin-information&amp;plugin=regenerate-thumbnails&amp;TB_iframe=true&amp;width=750&amp;height=600" class="thickbox">' . esc_html__('Regenerate Thumbnails', 'youplay') . '</a>'
            );
            ?>
        </li>
        <li><?php esc_html_e('Importing a demo provides images, pages, posts, theme options, widgets and more. . Please, wait before the process end, it may take a while.', 'youplay'); ?></li>
    </ol>
</div>

<div class="nk-import-result"></div>

<div class="feature-section theme-browser rendered nk-demos-list">
    <?php
    // Loop through all demos

    $active_demo = nk_admin()->get_option('active_demo', null);

    foreach ( $demos as $name => $demo ) {
        $is_active = false;
        if ($active_demo && $active_demo === $name) {
            $is_active = true;
        }
        ?>
        <div class="theme <?php echo $is_active ? 'active' : ''; ?>">
            <div class="theme-wrapper">
                <a class="theme-screenshot" target="_blank" href="<?php echo esc_attr($demo['preview']); ?>">
                    <img src="<?php echo esc_attr($demo['thumbnail']); ?>" />
                </a>
                <?php printf( '<a target="_blank" href="%1s"><span class="more-details">%2s</span></a>', $demo['preview'], esc_html__( 'Live Preview', 'youplay' ) ); ?>
                <div class="theme-progress">
                    <div class="theme-progress-bar"></div>
                </div>
                <h3 class="theme-name" id="<?php echo $name; ?>"><?php echo esc_html($demo['title']); ?></h3>
                <div class="theme-actions">
                    <?php printf( '<a class="button button-primary button-demo" data-demo="%s" href="#">%s</a>', strtolower( $name ), esc_html__( 'Import', 'youplay' ) ); ?>
                    <span class="spinner"></span>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
