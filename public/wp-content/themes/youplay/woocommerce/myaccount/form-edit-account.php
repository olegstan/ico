<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_edit_account_form' ); ?>

<form action="" method="post">

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<div class="form-row form-row-first">
		<label for="account_first_name"><?php _e( 'First name', 'youplay' ); ?> <span class="required">*</span></label>
		<div class="youplay-input mb-0">
			<input type="text" class="input-text" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
		</div>
	</div>
	<div class="form-row form-row-last">
		<label for="account_last_name"><?php _e( 'Last name', 'youplay' ); ?> <span class="required">*</span></label>
		<div class="youplay-input mb-0">
			<input type="text" class="input-text" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
		</div>
	</div>
	<div class="clear"></div>

	<div class="form-row form-row-wide">
		<label for="account_email"><?php _e( 'Email address', 'youplay' ); ?> <span class="required">*</span></label>
		<div class="youplay-input mb-0">
			<input type="email" class="input-text" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
		</div>
	</div>

    <br>
	<fieldset>
		<h3><?php _e( 'Password Change', 'youplay' ); ?></h3>

		<div class="form-row form-row-wide">
			<label for="password_current"><?php _e( 'Current Password (leave blank to leave unchanged)', 'youplay' ); ?></label>
			<div class="youplay-input mb-0">
				<input type="password" class="input-text" name="password_current" id="password_current" />
			</div>
		</div>
		<div class="form-row form-row-wide">
			<label for="password_1"><?php _e( 'New Password (leave blank to leave unchanged)', 'youplay' ); ?></label>
			<div class="youplay-input mb-0">
				<input type="password" class="input-text" name="password_1" id="password_1" />
			</div>
		</div>
		<div class="form-row form-row-wide">
			<label for="password_2"><?php _e( 'Confirm New Password', 'youplay' ); ?></label>
			<div class="youplay-input mb-0">
				<input type="password" class="input-text" name="password_2" id="password_2" />
			</div>
		</div>
	</fieldset>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

    <br><br>
	<p>
		<?php wp_nonce_field( 'save_account_details' ); ?>
		<button class="btn btn-default" name="save_account_details"><?php _e( 'Save changes', 'youplay' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>

</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
