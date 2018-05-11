<div class="rtmedia-container">
	<?php
	do_action( 'rtmedia_before_album_gallery' );

	$title = get_rtmedia_gallery_title();
	?>

	<div id="rtm-gallery-title-container" class="clearfix">
		<h2 class="rtm-gallery-title h3 mt-0">
			<?php
			if ( $title ) {
				echo esc_html( $title );
			} else {
				esc_html_e( 'Album List', 'youplay' );
			}
			?>
		</h2>

		<div id="rtm-media-options" class="rtm-media-options">
			<?php do_action( 'rtmedia_album_gallery_actions' ); ?>
		</div>
	</div>

	<?php do_action( 'rtmedia_after_album_gallery_title' ); ?>

	<div id="rtm-media-gallery-uploader" class="rtm-media-gallery-uploader">
		<?php rtmedia_uploader( array( 'is_up_shortcode' => false ) ); ?>
	</div>

	<?php if ( have_rtmedia() ) { ?>

		<ul class="rtmedia-list rtmedia-album-list clearfix">
			<?php while ( have_rtmedia() ) : rtmedia(); ?>
				<?php include( 'album-gallery-item.php' ); ?>
			<?php endwhile; ?>
		</ul>

		<div class="rtmedia_next_prev rtm-load-more clearfix">
			<!-- these links will be handled by backbone -->
			<?php
			global $rtmedia;

			$general_options = $rtmedia->options;

			if ( isset( $rtmedia->options['general_display_media'] ) && 'pagination' === $general_options['general_display_media'] ) {
				rtmedia_media_pagination();
			} else {
				$display = '';

				if ( rtmedia_offset() + rtmedia_per_page_media() < rtmedia_count() ) {
					$display = 'display:block;';
				} else {
					$display = 'display:none;';
				}
				?>
				<a id="rtMedia-galary-next" style='<?php echo esc_attr( $display ); ?>'
				   href="<?php echo esc_url( rtmedia_pagination_next_link() ); ?>"><?php esc_html_e( 'Load More', 'youplay' ); ?></a>
				<?php
			}
			?>
		</div><!--/.rtmedia_next_prev-->
	<?php } else { ?>
		<p class="rtmedia-no-media-found">
			<?php
			esc_html_e( 'Sorry! There\'s no media found for the request!', 'youplay' );
			?>
		</p>
	<?php } ?>

	<?php do_action( 'rtmedia_after_album_gallery' ); ?>
</div>

<!-- template for single media in gallery -->
<script id="rtmedia-gallery-item-template" type="text/template">
	<div class="rtmedia-item-thumbnail">
		<a href="media/<%= id %>">
			<img src="<%= guid %>">
		</a>
	</div>

	<div class="rtmedia-item-title">
		<h4 title="<%= media_title %>">
			<a href="media/<%= id %>">
				<%= media_title %>
			</a>
		</h4>
	</div>
</script>
<!-- rtmedia_actions remained in script tag -->
