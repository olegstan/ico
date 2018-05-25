<?php
// Theme Activation Action
add_action('wp_ajax_nk_theme_activation_action', 'nk_theme_activation_action');
function nk_theme_activation_action () {
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    $code = isset($_POST['code']) ? $_POST['code'] : null;
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $refresh_token = isset($_POST['refresh_token']) ? $_POST['refresh_token'] : null;
    $edd_license = isset($_POST['edd_license']) ? $_POST['edd_license'] : null;
    $edd_name = nk_theme()->theme_dashboard()->options['edd_name'];

    if($type == 'deactivate') {
        if (nk_theme()->theme_dashboard()->get_option('edd_license')) {
            $response = wp_remote_get('https://nkdev.info/?edd_action=deactivate_license&item_name=' . urlencode($edd_name) . '&license=' . esc_html(nk_theme()->theme_dashboard()->get_option('edd_license')) . '&url=' . esc_url(home_url('/')));

            if(wp_remote_retrieve_response_code($response) == 200 && wp_remote_retrieve_body($response)) {
                $response = json_decode(wp_remote_retrieve_body($response));

                if (isset($response->success) && isset($response->license) && $response->license == 'deactivated') {
                    nk_theme()->theme_dashboard()->update_option('edd_license', null);
                    echo 'ok';
                } else if (isset($response->error)) {
                    echo $response->error;
                }
            } else {
                if(is_wp_error($response)) {
                    echo 'Error: ' . $response->get_error_message();
                } else {
                    echo 'Error: failed connection.';
                }
            }
        }
    } else {
        $response = false;

        // verify purchase code
        if ($edd_license != null && $edd_name) {
            $response = wp_remote_get('https://nkdev.info/?edd_action=activate_license&item_name=' . urlencode($edd_name) . '&license=' . esc_html($edd_license) . '&url=' . esc_url(home_url('/')));
        } else {
            echo 'Error: purchase code was not specified.';
        }

        if ($response) {
            if(wp_remote_retrieve_response_code($response) == 200 && wp_remote_retrieve_body($response)) {
                $response = json_decode(wp_remote_retrieve_body($response));

                if (isset($response->success)) {
                    if (isset($response->error)) {
                        echo 'Error: ' . $response->error;
                    } else {
                        if ($response->license && $response->license == 'valid') {
                            nk_theme()->theme_dashboard()->update_option('edd_license', $edd_license);
                            echo 'ok';
                        } else {
                            echo 'Error: Something went wrong. Please, contact us here https://nk.ticksy.com/';
                        }
                    }
                } else if (isset($response->error)) {
                    echo $response->response;
                }
            } else {
                if(is_wp_error($response)) {
                    echo 'Error: ' . $response->get_error_message();
                } else {
                    echo 'Error: failed connection.';
                }
            }
        }
    }

    die();
}
