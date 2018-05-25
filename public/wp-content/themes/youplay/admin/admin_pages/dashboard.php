<?php
// minimum requirements
$min_requirements = nk_admin()->pages()->pages_data['min_requirements'];
?>

<div class="nk-dashboard-widgets">
    <?php if (nk_admin()->theme_id) : ?>
    <div class="nk-dashboard-widget">
        <div class="nk-dashboard-widget-title">
            <?php
                if(nk_admin()->activation()->active) {
                    ?>
                    <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e('Activated', 'youplay'); ?></span>
                    <mark class="yes"><?php esc_html_e('Activation', 'youplay'); ?></mark>
                    <?php
                } else {
                    ?>
                    <span class="nk-dashboard-widget-title-badge error"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('Not Activated', 'youplay'); ?></span>
                    <mark class="error"><?php esc_html_e('Activation', 'youplay'); ?></mark>
                    <?php
                }
            ?>
        </div>
        <div class="nk-dashboard-widget-content">
            <p>
                <?php echo wp_kses(sprintf(__('By activating %s you will unlock premium options - <strong>direct theme updates</strong> and access to <strong>official support</strong>.', 'youplay'), nk_admin()->theme_name), array(
                    'strong' => array()
                )); ?>
            </p>

            <?php
                $new_purchase_codes = nk_admin()->activation()->new_purchase_codes;
                $new_token = nk_admin()->activation()->new_token;
                $new_refresh_token = nk_admin()->activation()->new_refresh_token;
                if(nk_admin()->activation()->active) {
                    ?>
                    <input id="nk-theme-deactivate-reload" type="hidden" value="<?php echo esc_attr(admin_url('admin.php?page=nk-theme')); ?>">
                    <p class="clear"></p>
                    <span id="nk-theme-deactivate-license" class="button button-secondary pull-left">
                        <?php echo sprintf(esc_html__('Deactivate %s', 'youplay'), nk_admin()->theme_name); ?>
                    </span>
                    <span class="spinner pull-left"></span>
                    <div class="clear"></div>
                    <?php
                } else if((is_array($new_purchase_codes) || $new_purchase_codes == 'false') && $new_token && $new_refresh_token) {
                    // if no one license found
                    if($new_purchase_codes == 'false' || count($new_purchase_codes) == 0) {
                        ?>
                        <p>
                            <mark class="error">
                                <?php esc_html_e('You haven\'t any valid license.', 'youplay'); ?>
                            </mark>
                            <em><a href="https://wp.nkdev.info/_api/?item_id=<?php echo nk_admin()->theme_id; ?>&type=redirect" target="_blank">
                                <?php echo sprintf(esc_html__('Purchase %s License', 'youplay'), nk_admin()->theme_name); ?>
                            </a></em>
                        </p>
                        <?php
                    }

                    // if have one license
                    else if (count($new_purchase_codes) == 1) {
                        ?>
                        <p>
                            <?php echo wp_kses(sprintf(__('You have <strong>1</strong> valid license at <strong>%s</strong>.', 'youplay'), $new_purchase_codes[0]['sold_at']), array(
                                'strong' => array()
                            )); ?>
                        </p>
                        <input id="nk-theme-activate-license" type="text" value="<?php echo esc_attr($new_purchase_codes[0]['code']); ?>" disabled>
                        <input id="nk-theme-activate-reload" type="hidden" value="<?php echo esc_attr(admin_url('admin.php?page=nk-theme')); ?>">
                        <input id="nk-theme-activate-token" type="hidden" value="<?php echo esc_attr($new_token); ?>">
                        <input id="nk-theme-refresh-token" type="hidden" value="<?php echo esc_attr($new_refresh_token); ?>">
                        <p class="clear"></p>
                        <span id="nk-theme-activate" class="button button-primary pull-left"><?php esc_html_e('Activate', 'youplay'); ?></span>
                        <span class="spinner pull-left"></span>
                        <div class="clear"></div>
                        <?php
                    }

                    // if > 1 license keys
                    else if (count($new_purchase_codes) > 1) {
                        ?>
                        <p>
                            <?php echo wp_kses(sprintf(__('You have <strong>%s</strong> valid licenses. Choose one:', 'youplay'), count($new_purchase_codes)), array(
                                'strong' => array()
                            )); ?>
                        </p>
                        <select id="nk-theme-activate-license">
                            <?php
                            foreach ($new_purchase_codes as $key) {
                                ?><option value="<?php echo esc_attr($key['code']); ?>">
                                    <?php echo esc_html($key['code']); ?> - <?php echo esc_html($key['sold_at']); ?>
                                </option><?php
                            }
                            ?>
                        </select>
                        <input id="nk-theme-activate-reload" type="hidden" value="<?php echo esc_attr(admin_url('admin.php?page=nk-theme')); ?>">
                        <input id="nk-theme-activate-token" type="hidden" value="<?php echo esc_attr($new_token); ?>">
                        <input id="nk-theme-refresh-token" type="hidden" value="<?php echo esc_attr($new_refresh_token); ?>">
                        <p class="clear"></p>
                        <span id="nk-theme-activate" class="button button-primary pull-left"><?php esc_html_e('Activate', 'youplay'); ?></span>
                        <span class="spinner pull-left"></span>
                        <div class="clear"></div>
                        <?php
                    }
                } else {
                    ?>
                    <p>
                        <a href="<?php echo esc_attr('https://wp.nkdev.info/_api?item_id=' . nk_admin()->theme_id . '&type=activation_check&redirect_uri=' . urlencode(admin_url('admin.php?page=nk-theme'))); ?>" class="button button-primary">
                            <?php echo sprintf(esc_html__('Activate %s', 'youplay'), nk_admin()->theme_name); ?>
                        </a>
                    </p>
                    <p>
                        <em>
                            <?php esc_html_e('Don\'t have valid license yet?', 'youplay'); ?>
                            <a href="https://wp.nkdev.info/_api/?item_id=<?php echo nk_admin()->theme_id; ?>&type=redirect" target="_blank">
                                <?php echo sprintf(esc_html__('Purchase %s License', 'youplay'), nk_admin()->theme_name); ?>
                            </a>
                        </em>
                    </p>
                    <?php
                }
            ?>
        </div>
    </div>
    <div class="nk-dashboard-widget">
        <div class="nk-dashboard-widget-title">
            <?php
            if(nk_admin()->updater()->is_update_available()) {
                ?>
                <span class="nk-dashboard-widget-title-badge error"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('Update Available', 'youplay'); ?></span>
                <mark class="error"><?php esc_html_e('Update', 'youplay'); ?></mark>
                <?php
            } else {
                ?>
                <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e('Theme is Up to Date', 'youplay'); ?></span>
                <mark class="yes"><?php esc_html_e('Update', 'youplay'); ?></mark>
                <?php
            }
            ?>
        </div>
        <div class="nk-dashboard-widget-content">
            <p>
                <strong><?php _e( 'Installed Version:', 'youplay' ); ?></strong>
                <br>
                <?php echo nk_admin()->theme_version; ?>
            </p>
            <p>
                <strong><?php _e( 'Latest Version:', 'youplay' ); ?></strong>
                <br>
                <?php echo nk_admin()->updater()->get_latest_theme_version(); ?>
            </p>
            <?php
                if(nk_admin()->updater()->is_update_available()) {
                    if(nk_admin()->activation()->active) {
                        $update_url = wp_nonce_url( admin_url('update.php?action=upgrade-theme&amp;theme=' . urlencode(nk_admin()->theme_slug)), 'upgrade-theme_' . nk_admin()->theme_slug );
                        ?>
                        <a href="<?php echo esc_attr($update_url); ?>" class="button button-primary">
                            <?php esc_html_e('Update Now', 'youplay'); ?>
                        </a>
                        <?php
                    } else {
                        ?>
                        <span class="button button-primary disabled">
                            <?php esc_html_e('Update Now', 'youplay'); ?>
                        </span>
                        <?php
                    }
                } else {
                    ?>
                    <span class="button disabled">
                        <?php esc_html_e('Update Now', 'youplay'); ?>
                    </span>
                    <?php
                }
            ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="nk-dashboard-widget">
        <?php

        // requirements check
        $memory = nk_admin()->let_to_num( WP_MEMORY_LIMIT );
        $min_memory = nk_admin()->let_to_num( $min_requirements['memory_limit'] );
        $req_memory_limit = $memory >= $min_memory;

        $req_php_ver = true;
        if(function_exists('phpversion')) {
            $php_ver = phpversion();
            $req_php_ver = version_compare($php_ver, $min_requirements['php_version'], '>=');
        }

        $req_max_exec_time = true;
        if(function_exists('ini_get')) {
            $time_limit = ini_get('max_execution_time');
            $req_max_exec_time = $time_limit >= $min_requirements['max_execution_time'];
        }

        $req_wp_remote_get = true;
        $wp_remote_get_response = wp_remote_get('https://wp.nkdev.info/_api/');
        if(is_wp_error($wp_remote_get_response)) {
            $req_wp_remote_get = false;
        }

        $req_all_ok = $req_memory_limit && $req_php_ver && $req_max_exec_time && $req_wp_remote_get;

        ?>

        <div class="nk-dashboard-widget-title">
            <?php
            if($req_all_ok) {
                ?>
                <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e('No Problems', 'youplay'); ?></span>
                <mark class="yes"><?php esc_html_e('Recommendations', 'youplay'); ?></mark>
                <?php
            } else {
                ?>
                <span class="nk-dashboard-widget-title-badge error"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('Some Problems', 'youplay'); ?></span>
                <mark class="error"><?php esc_html_e('Recommendations', 'youplay'); ?></mark>
                <?php
            }
            ?>
        </div>
        <div class="nk-dashboard-widget-content">
            <div class="nk-theme-requirements">
                <table class="widefat" cellspacing="0">
                    <tbody>
                        <tr>
                            <td><?php _e( 'WP Memory Limit:', 'youplay' ); ?></td>
                            <td><?php
                            if ($req_memory_limit) {
                                echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . size_format($memory) . '</mark>';
                            } else {
                                echo '<mark class="error nk-drop"><i class="fa fa-times-circle"></i> ' . size_format($memory) . ' ';
                                echo '<small>' . esc_html__('[more info]', 'youplay') . '</small>';
                                echo '<span class="nk-drop-cont">';
                                echo sprintf(
                                        esc_html__( 'For normal usage will be enough 128 MB, but for importing demo we recommend setting memory to at least %s.', 'youplay' ),
                                        '<strong>' . size_format($min_memory) . '</strong>'
                                    );
                                echo ' <br> ';
                                echo sprintf(
                                        esc_html__( 'See more: %s', 'youplay' ),
                                        sprintf('<a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">%s</a>', esc_html__('Increasing memory allocated to PHP.', 'youplay'))
                                    );
                                echo '</span>';
                                echo '</mark>';
                            }
                            ?></td>
                        </tr>
                        <tr>
                            <td><?php _e( 'PHP Version:', 'youplay' ); ?></td>
                            <td><?php
                                if (function_exists('phpversion')) {
                                    if ($req_php_ver) {
                                        echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . $php_ver . '</mark>';
                                    } else {
                                        echo '<mark class="error nk-drop">';
                                        echo '<i class="fa fa-times-circle"></i> ' . $php_ver;
                                        echo ' <small>' . esc_html__('[more info]', 'youplay') . '</small>';
                                        echo '<span class="nk-drop-cont">';
                                        echo sprintf( esc_html__( 'We recommend upgrade php version to at least %s.', 'youplay' ), $min_requirements['php_version'] );
                                        echo '</span>';
                                        echo '</mark>';
                                    }
                                }
                            ?></td>
                        </tr>
                        <tr>
                            <td><?php _e( 'PHP Time Limit:', 'youplay' ); ?></td>
                            <td>
                            <?php if (function_exists('ini_get')) :
                                if ($req_max_exec_time) {
                                    echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . $time_limit . '</mark>';
                                } else {
                                    echo '<mark class="error nk-drop">';
                                    echo '<i class="fa fa-times-circle"></i> ' . $time_limit;
                                    echo ' <small>' . esc_html__('[more info]', 'youplay') . '</small>';
                                    echo '<span class="nk-drop-cont">';
                                    echo sprintf( esc_html__( 'We recommend setting max execution time to at least %s.', 'youplay' ), $min_requirements['max_execution_time'] );
                                    echo ' <br> ';
                                    echo sprintf(
                                        esc_html__('See more: %s', 'youplay'),
                                        sprintf('<a href="http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded" target="_blank">%s</a>', esc_html__('Increasing max execution to PHP', 'youplay'))
                                    );
                                    echo '</span>';
                                    echo '</mark>';
                                }
                            endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php _e( 'WP Remote Get:', 'youplay' ); ?></td>
                            <td><?php
                                if ($req_wp_remote_get) {
                                    echo '<mark class="yes"><i class="fa fa-check-circle"></i> </mark>';
                                } else {
                                    echo '<mark class="error nk-drop">';
                                    echo '<i class="fa fa-times-circle"></i> ' . esc_html__('Failed', 'youplay');
                                    echo ' <small>' . esc_html__('[more info]', 'youplay') . '</small>';
                                    echo '<span class="nk-drop-cont">';
                                    echo esc_html__( 'wp_remote_get() failed. Some theme features may not work. Please contact your hosting provider and make sure that https://wp.nkdev.info/_api/ is not blocked.', 'youplay' );
                                    echo '</span>';
                                    echo '</mark>';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td><?php _e( 'Child Theme:', 'youplay' ); ?></td>
                            <td><?php
                                if(nk_admin()->theme_is_child) {
                                    echo '<mark class="yes"><i class="fa fa-check-circle"></i></mark>';
                                } else {
                                    ?>
                                    <mark class="nk-drop">
                                        <i class="fa fa-times-circle"></i>
                                        <small><?php echo esc_html__('[more info]', 'youplay'); ?></small>
                                        <span class="nk-drop-cont">
                                            <?php esc_html_e('We recommend use child theme to prevent loosing your customizations after theme update.', 'youplay'); ?>
                                        </span>
                                    </mark>
                                    <?php
                                }
                            ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="nk-dashboard-widget">
        <div class="nk-dashboard-widget-title">
            <mark><?php esc_html_e('Support', 'youplay'); ?></mark>
        </div>
        <div class="nk-dashboard-widget-content">
            <p><?php esc_html_e('Have troubles, found a bug or want to suggest something? Write in support system.', 'youplay'); ?></p>
            <p><em><?php esc_html_e('Make sure, you have a valid license, otherwise we don\'t provide support.', 'youplay'); ?></em></p>
            <?php
            printf('<a href="%s" class="button button-primary" target="_blank">%s</a>', 'https://nk.ticksy.com/', esc_html__('Get Support', 'youplay'));
            ?>
        </div>
    </div>
    <div class="nk-dashboard-widget">
        <div class="nk-dashboard-widget-title">
            <mark><?php esc_html_e('Newsletter', 'youplay'); ?></mark>
        </div>
        <div class="nk-dashboard-widget-content">
            <!-- Begin MailChimp Signup Form -->
            <div id="mc_embed_signup">
                <form action="//nkdev.us11.list-manage.com/subscribe/post?u=d433160c0c43dcf8ecd52402f&amp;id=7eafafe8f0" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                        <div class="mc-field-group">
                            <p><label for="mce-EMAIL"><?php esc_html_e('Email Address:', 'youplay'); ?></label></p>
                            <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                        </div>
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_d433160c0c43dcf8ecd52402f_7eafafe8f0" tabindex="-1" value=""></div>

                        <br>
                        <div class="clear">
                            <button type="submit" name="subscribe" id="mc-embedded-subscribe" class="button button-primary"><?php esc_html_e('Subscribe', 'youplay'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <!--End mc_embed_signup-->

            <br>
            <p><em><?php esc_html_e('Join our newsletter to receive news, updates, new products and freebies in your inbox.', 'youplay'); ?></em></p>

            <?php
            wp_enqueue_script('mc-validate', nk_admin()->admin_uri . '/assets/js/mc-validate.js', array('jquery'), '', true);
            ?>
        </div>
    </div>
</div>
