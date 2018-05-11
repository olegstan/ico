<?php
/**
 * @package Youplay
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('news-one'); ?>>
	<div class="entry-content description">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'youplay' ),
				'after'  => '</div>',
			) );
		?>
	</div>

	<?php youplay_post_sharing(); ?>

	<footer class="entry-footer">
		<?php youplay_entry_footer(); ?>
	</footer>
</article>
