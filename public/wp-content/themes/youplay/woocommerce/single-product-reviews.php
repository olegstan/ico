<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
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
 * @version     3.2.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $product;

if ( ! comments_open() ) {
	return;
}

?>
<div class="reviews-block">
	<h2><?php
        if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && ( $count = $product->get_review_count() ) ) {
            /* translators: 1: reviews count 2: product name */
            printf( esc_html( _n( 'Review %1$s', 'Reviews %1$s', $count, 'youplay' ) ), ' <small>(' . esc_html( $count ) . ')</small>' );
        } else {
            esc_html__( 'Reviews', 'youplay' );
        }
	?></h2>

	<div class="reviews-list">

		<?php if ( have_comments() ) : ?>

			<ul class="reviews-list">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array(
					'callback'   => 'woocommerce_comments',
					'short_ping' => true,
					'avatar_size'=> 90
				) ) ); ?>
			</ul>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="woocommerce-pagination">';
				paginate_comments_links( apply_filters( 'woocommerce_comment_pagination_args', array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;',
					'type'      => 'list',
				) ) );
				echo '</nav>';
			endif; ?>

		<?php else : ?>

			<p class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'youplay' ); ?></p>

		<?php endif; ?>
	</div>

    <div class="clear"></div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>

		<?php
			$commenter = wp_get_current_commenter();

			$comment_form = array(
				'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'youplay' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'youplay' ), get_the_title() ),
                'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'youplay' ),
				'class_submit'         => 'btn btn-default pull-right',
				'logged_in_as'         => '',
				'comment_notes_before' => '',
				'comment_notes_after'  => '',
				'type'                 => 'review',
                'comment_field'        => '',
			);

            if ( $account_page_url = wc_get_page_permalink( 'myaccount' ) ) {
                $comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a review.', 'youplay' ), esc_url( $account_page_url ) ) . '</p>';
            }

            $comment_form['comment_field'] .= '<div class="youplay-textarea"><textarea id="comment" name="comment" rows="5" aria-required="true" placeholder="' . esc_html__( 'Your Review', 'youplay' ) . '"></textarea></div>';

			if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
				$comment_form['comment_field'] .= '<div class="youplay-rating pull-right mb-0">
                <input type="radio" id="rating-5" name="rating" value="5" aria-required="true" required>
                <label for="rating-5"><i class="fa fa-star"></i></label>
                <input type="radio" id="rating-4" name="rating" value="4" aria-required="true" required>
                <label for="rating-4"><i class="fa fa-star"></i></label>
                <input type="radio" id="rating-3" name="rating" value="3" aria-required="true" required>
                <label for="rating-3"><i class="fa fa-star"></i></label>
                <input type="radio" id="rating-2" name="rating" value="2" aria-required="true" required>
                <label for="rating-2"><i class="fa fa-star"></i></label>
                <input type="radio" id="rating-1" name="rating" value="1" aria-required="true" required>
                <label for="rating-1"><i class="fa fa-star"></i></label>
                <input type="radio" id="rating-0" name="rating" value="" aria-required="true" required checked>
              </div>
              <div class="clearfix"></div>';
			}

			youplay_comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
		?>

	<?php else : ?>

		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'youplay' ); ?></p>

	<?php endif; ?>

	<div class="clear"></div>
</div>
