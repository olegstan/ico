<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

global $post, $product;

$attachment_ids = $product->get_gallery_image_ids();

if($attachment_ids) {
   $carousel = '';
   foreach($attachment_ids as $img_id) {
      $carousel .= '[yp_carousel_img img_src="' . $img_id . '"]';
   }

   echo do_shortcode('[yp_carousel style="3"]' . $carousel . '[/yp_carousel]');
}
?>
