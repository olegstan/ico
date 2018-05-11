<?php
/**
 * BuddyPress - Users Messages
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="row">
	<div class="col-md-9">
		<div class="item-list-tabs no-ajax clearfix" id="subnav" role="navigation">
			<ul class="pagination pagination-sm">
				<?php youplay_bp_get_options_nav(); ?>
			</ul>
		</div><!-- .item-list-tabs -->
	</div>
	<div class="col-md-3">
		<?php if ( bp_is_messages_inbox() || bp_is_messages_sentbox() ) : ?>

			<div class="message-search"><?php youplay_bp_message_search_form(); ?></div>

		<?php endif; ?>
	</div>
</div>

<?php
switch ( bp_current_action() ) :

	// Inbox/Sentbox
	case 'inbox'   :
	case 'sentbox' :

		/**
		 * Fires before the member messages content for inbox and sentbox.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_messages_content' ); ?>

		<div class="messages">
			<?php bp_get_template_part( 'members/single/messages/messages-loop' ); ?>
		</div><!-- .messages -->

		<?php

		/**
		 * Fires after the member messages content for inbox and sentbox.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_member_messages_content' );
		break;

	// Single Message View
	case 'view' :
		bp_get_template_part( 'members/single/messages/single' );
		break;

	// Compose
	case 'compose' :
		bp_get_template_part( 'members/single/messages/compose' );
		break;

	// Sitewide Notices
	case 'notices' :

		/**
		 * Fires before the member messages content for notices.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_messages_content' ); ?>

		<div class="messages">
			<?php bp_get_template_part( 'members/single/messages/notices-loop' ); ?>
		</div><!-- .messages -->

		<?php

		/**
		 * Fires after the member messages content for inbox and sentbox.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_member_messages_content' );
		break;

	// Any other
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
