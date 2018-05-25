<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Youplay
 */

get_header(); ?>

    <!-- Content -->
    <section class="content-wrap full youplay-404">

		<div class="youplay-banner banner-top">
	    <?php if ( yp_opts('404_background') ): ?>
		  	<div class="image" style="background-image: url(<?php echo esc_url(yp_opts('404_background')); ?>)"></div>
	    <?php endif; ?>

		  <div class="info">
		    <div>
		      <div class="container align-center">
		      	<?php echo wp_kses_post(yp_opts('404_content')); ?>

		      	<?php
			      	if( yp_opts('404_search') ) {
								get_search_form();
			      	}
		      	?>
		      </div>
		    </div>
		  </div>
		</div>

<?php get_footer(); ?>
