<?php
/**
 * BuddyPress - Users Blogs
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="item-list-tabs clearfix" id="subnav" role="navigation">
	<ul>

		<li>
			<ul class="pagination pagination-sm">
				<?php youplay_bp_get_options_nav(); ?>
			</ul>
		</li>

		<li id="blogs-order-select" class="last filter">

			<label for="blogs-order-by"><?php _e( 'Order By:', 'youplay' ); ?></label>
			<div class="youplay-select">
				<select id="blogs-order-by">
					<option value="active"><?php _e( 'Last Active', 'youplay' ); ?></option>
					<option value="newest"><?php _e( 'Newest', 'youplay' ); ?></option>
					<option value="alphabetical"><?php _e( 'Alphabetical', 'youplay' ); ?></option>

					<?php

					/**
					 * Fires inside the members blogs order options select input.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_member_blog_order_options' ); ?>

				</select>
			</div>
		</li>
	</ul>
</div><!-- .item-list-tabs -->

<?php
switch ( bp_current_action() ) :

	// Home/My Blogs
	case 'my-sites' :

		/**
		 * Fires before the display of member blogs content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_blogs_content' ); ?>

		<div class="blogs myblogs">

			<?php bp_get_template_part( 'blogs/blogs-loop' ) ?>

		</div><!-- .blogs.myblogs -->

		<?php

		/**
		 * Fires after the display of member blogs content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_member_blogs_content' );
		break;

	// Any other
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch;
