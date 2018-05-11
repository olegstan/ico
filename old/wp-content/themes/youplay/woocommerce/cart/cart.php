<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
    <?php do_action( 'woocommerce_before_cart_table' ); ?>

    <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents">

        <?php do_action( 'woocommerce_before_cart_contents' ); ?>

        <?php
        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key )
                ?>
                <div class="woocommerce-cart-form__cart-item item angled-bg <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                  <div class="row">
                    <div class="col-lg-2 col-md-3 col-xs-4">
                      <div class="angled-img">
                      <?php
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                        $discount = yp_woo_discount_badge( $_product );

                        if ( ! $product_permalink ) {
                          echo '<div class="img">' . $thumbnail . $discount . '</div>';
                        } else {
                          printf( '<a href="%s" class="img" style="display: block;">%s %s</a>', esc_url( $product_permalink ), $thumbnail, $discount );
                        }
                      ?>
                      </div>
                    </div>
                    <div class="col-lg-10 col-md-9 col-xs-8">
                      <div class="row">
                        <div class="col-xs-12 col-md-8">
                          <h4>
                            <?php
                              if ( !$product_permalink ) {
                                echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;';
                              } else {
                                echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s </a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key );
                              }
                            ?>
                          </h4>

                          <?php
                            // Meta data
                            echo WC()->cart->get_item_data( $cart_item );

                            // Backorder notification
                            if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
                              echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'youplay' ) . '</p>';
                            }
                          ?>

                          <?php echo yp_get_rating( $_product->get_average_rating() ); ?>

                            <div class="visible-xs-block visible-sm-block mt-20"></div>
                        </div>
                        <div class="col-xs-6 col-md-2 align-right">
                            <div class="mt-10"></div>
                            <?php
                            if ( $_product->is_sold_individually() ) {
                                $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                            } else {
                                $product_quantity = woocommerce_quantity_input( array(
                                    'input_name'  => "cart[{$cart_item_key}][qty]",
                                    'input_value' => $cart_item['quantity'],
                                    'max_value'   => $_product->get_max_purchase_quantity(),
                                    'min_value'   => '0'
                                ), $_product, false );
                            }

                            echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
                            ?>
                            <div class="mt-10"></div>
                        </div>
                        <div class="col-xs-6 col-md-2 align-right">
                          <div class="price">
                            <?php
                            echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                            ?>
                          </div>
                          <?php
                            $isRTL = yp_opts('general_rtl');
                            echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="glyphicon glyphicon-remove" style="font-size: 1.7rem; margin-top: 5px; margin-' . ($isRTL?'left':'right') . ': 20px; text-decoration: none;" aria-label="%s" data-product_id="%s" data-product_sku="%s"></a>',
                                esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
                                __( 'Remove this item', 'youplay' ),
                                esc_attr( $product_id ),
                                esc_attr( $_product->get_sku() )
                              ),
                              $cart_item_key
                            );
                          ?>
                            <div class="mt-10"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
            }
        }

        do_action( 'woocommerce_cart_contents' );
        ?>

        <div class="actions">
            <?php if ( wc_coupons_enabled() ) { ?>
                <div class="coupon pull-left">
                    <div class="youplay-input dib">
                        <input type="text" name="coupon_code" id="coupon_code" value="" placeholder="<?php esc_html_e( 'Coupon code', 'youplay' ); ?>" />
                    </div>
                    <span class="btn btn-default">
                        <?php _e( 'Apply Coupon', 'youplay' ); ?>
                        <input type="submit" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'youplay' ); ?>" />
                    </span>

                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                </div>
            <?php } ?>

            <span class="btn btn-default pull-right">
                <?php esc_html_e( 'Update Cart', 'youplay' ); ?>
                <input type="submit" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'youplay' ); ?>" />
             </span>

            <?php do_action( 'woocommerce_cart_actions' ); ?>

            <?php wp_nonce_field( 'woocommerce-cart' ); ?>
        </div>
        <div class="clearfix"></div>

        <?php do_action( 'woocommerce_after_cart_contents' ); ?>

    </div>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">
    <?php
    /**
     * woocommerce_cart_collaterals hook.
     *
     * @hooked woocommerce_cross_sell_display
     * @hooked woocommerce_cart_totals - 10
     */
    do_action( 'woocommerce_cart_collaterals' );
    ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
