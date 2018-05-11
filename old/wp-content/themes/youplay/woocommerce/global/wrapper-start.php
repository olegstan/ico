<?php
/**
 * Content wrappers
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$layout = yp_get_layout_data();

echo '<main class="' . yp_sanitize_class($layout['content_class']) . '">';
