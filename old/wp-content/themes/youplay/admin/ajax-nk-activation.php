<?php
// Theme Activation Action
add_action('wp_ajax_nk_activation_action', 'nk_activation_action');
function nk_activation_action () {
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    $code = isset($_POST['code']) ? $_POST['code'] : null;
    $token = isset($_POST['token']) ? $_POST['token'] : null;
    $refresh_token = isset($_POST['refresh_token']) ? $_POST['refresh_token'] : null;

    if($type == 'deactivate') {
        nk_admin()->update_option('activation_purchase_code', null);
        nk_admin()->update_option('activation_token', null);
        nk_admin()->update_option('refresh_token', null);
        echo 'ok';
    } else {
        if ($code != null && $token != null && $refresh_token != null) {
            // verify purchase code
            $response = wp_remote_get('https://wp.nkdev.info/_api/?type=check-license&item_id=' . nk_admin()->theme_id . '&license=' . $code);

            if(wp_remote_retrieve_response_code($response) == 200 && wp_remote_retrieve_body($response)) {
                $response = json_decode(wp_remote_retrieve_body($response));

                if($response->response == 'ok') {
                    nk_admin()->update_option('activation_purchase_code', $code);
                    nk_admin()->update_option('activation_token', $token);
                    nk_admin()->update_option('refresh_token', $refresh_token);
                    echo 'ok';
                } else if(isset($response->error)) {
                    echo $response->response;
                }
            } else {
                if(is_wp_error($response)) {
                    echo 'Error: ' . $response->get_error_message();
                } else {
                    echo 'Error: failed connection.';
                }
            }

        } else {
            echo 'Error: purchase code was not specified.';
        }
    }

    die();
}
