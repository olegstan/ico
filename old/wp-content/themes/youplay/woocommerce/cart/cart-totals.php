<?php
/**
 * Cart totals
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="cart_totals youplay-cart-table align-right <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">
	<br><br>

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<table class="table" cellspacing="0">

		<tr class="cart-subtotal">
			<th><?php _e( 'Subtotal', 'youplay' ); ?></th>
			<td><?php wc_cart_totals_subtotal_html(); ?></td>
		</tr>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
				<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<tr class="shipping">
				<th><?php _e( 'Shipping', 'youplay' ); ?></th>
				<td><?php woocommerce_shipping_calculator(); ?></td>
			</tr>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<tr class="fee">
				<th><?php echo esc_html( $fee->name ); ?></th>
				<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
			</tr>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
				? sprintf( ' <small>(' . __( 'estimated for %s', 'youplay' ) . ')</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
				: '';

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<tr class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<th><?php echo esc_html( $tax->label ) . $estimated_text; ?></th>
						<td data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr class="tax-total">
					<th><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></th>
					<td data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></td>
				</tr>
			<?php endif; ?>
		<?php endif; ?>

	</table>

	<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

	<div class="align-right h3 mr-20 mb-20">
		<?php esc_html_e( 'Total', 'youplay' ); ?>:
		<?php wc_cart_totals_order_total_html(); ?>
	</div>

	<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

	<div class="wc-proceed-to-checkout align-right">

		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>

	</div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>

<?php
$isRTL = yp_opts('general_rtl');
?>
<style type="text/css">
	.youplay-cart-table {
		width: 48%;
		float: <?php echo $isRTL ? 'left' : 'right'; ?>;
	}
	@media (max-width: 768px) {
		.youplay-cart-table {
			width: 100%;
			float: none;
		}
	}
</style>