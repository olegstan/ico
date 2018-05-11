<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Youplay
 */

?>

        <?php if ( !is_404() ) : ?>
            <div class="clearfix"></div>

            <!-- Footer -->
            <footer id="footer" class="<?php echo yp_opts('footer_parallax') ? 'youplay-footer-parallax' : ''; ?>">
                <div class="wrapper"
                    <?php if( yp_opts('footer_show_background') && yp_opts('footer_background') ): ?>
                         style="background-image: url(<?php echo esc_url(yp_opts('footer_background')); ?>)"
                    <?php endif; ?>
                >

                    <?php if(yp_opts('footer_widgets')): ?>
                        <?php
                        $widget_1_width = yp_opts('footer_widget_1_width');
                        $widget_2_width = yp_opts('footer_widget_2_width');
                        $widget_3_width = yp_opts('footer_widget_3_width');
                        $widget_4_width = yp_opts('footer_widget_4_width');
                        $any_widget_valid = $widget_1_width !== 0 && is_active_sidebar('footer_widgets_1') || $widget_2_width !== 0 && is_active_sidebar('footer_widgets_2') || $widget_3_width !== 0 && is_active_sidebar('footer_widgets_3') || $widget_4_width !== 0 && is_active_sidebar('footer_widgets_4');

                        if ($any_widget_valid) :
                        ?>
                            <div class="widgets">
                                <div class="container">
                                    <div class="row">

                                        <?php if ($widget_1_width !== 0 && is_active_sidebar('footer_widgets_1')) : ?>
                                            <div class="col-md-<?php echo yp_sanitize_class($widget_1_width); ?>">
                                                <?php dynamic_sidebar('footer_widgets_1'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($widget_2_width !== 0 && is_active_sidebar('footer_widgets_2')) : ?>
                                            <div class="col-md-<?php echo yp_sanitize_class($widget_2_width); ?>">
                                                <?php dynamic_sidebar('footer_widgets_2'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($widget_3_width !== 0 && is_active_sidebar('footer_widgets_3')) : ?>
                                            <div class="col-md-<?php echo yp_sanitize_class($widget_3_width); ?>">
                                                <?php dynamic_sidebar('footer_widgets_3'); ?>
                                            </div>
                                        <?php endif; ?>
                                        <?php if ($widget_4_width !== 0 && is_active_sidebar('footer_widgets_4')) : ?>
                                            <div class="col-md-<?php echo yp_sanitize_class($widget_4_width); ?>">
                                                <?php dynamic_sidebar('footer_widgets_4'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(yp_opts('footer_social')): ?>
                        <!-- Social Buttons -->
                        <div class="social">
                            <div class="container">

                                <?php echo wp_kses_post(yp_opts('footer_social_text')); ?>

                                <?php
                                    // chech how many social buttons anabled and create additional offset or hide all buttons
                                    $social_buttons = 4;
                                    if(!yp_opts('footer_social_fb')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_tw')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_gp')) {
                                        $social_buttons--;
                                    }
                                    if(!yp_opts('footer_social_yt')) {
                                        $social_buttons--;
                                    }
                                ?>

                                <?php if($social_buttons != 0): ?>
                                    <div class="social-icons">
                                        <?php if(yp_opts('footer_social_fb')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_fb_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_fb_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_fb_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_tw')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_tw_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_tw_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_tw_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_gp')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_gp_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_gp_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_gp_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_yt')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_yt_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_yt_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_yt_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_fifth')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_fifth_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_fifth_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_fifth_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                        <?php if(yp_opts('footer_social_sixth')): ?>
                                            <div class="social-icon">
                                                <a href="<?php echo esc_url(yp_opts('footer_social_sixth_url')); ?>" target="_blank">
                                                    <i class="<?php echo yp_sanitize_class(yp_opts('footer_social_sixth_icon')); ?>"></i>
                                                    <span><?php echo esc_html(yp_opts('footer_social_sixth_label')); ?></span>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                        <!-- /Social Buttons -->
                    <?php endif; ?>

                    <!-- Copyright -->
                    <div class="copyright">
                        <div class="container">
                            <?php echo wp_kses_post(yp_opts('footer_text')); ?>
                        </div>
                    </div>
                    <!-- /Copyright -->

                </div>
            </footer>
            <!-- /Footer -->
        <?php endif; ?>

    </section>
    <!-- /Content -->


    <!-- Search Block -->
    <div class="search-block">
        <a href="#" class="search-toggle glyphicon glyphicon-remove"></a>
        <?php get_search_form(); ?>
    </div>
    <!-- /Search Block -->

    <?php wp_footer(); ?>

<div class="modal fade" id="JackpotModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">You have won jackpot!</h4>
      </div>
      <div class="modal-body">
		  <div id="JackpotModalBodyDiv">
			Congratulations! You have won jackpot! Your creadits has been increased!
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
	
jQuery( document ).ready(function(){
	
	// payment page referal
		
	jQuery.ajax({
			url: "https://api.spin2spin.com/api/get/user",
			data: {'data[hall_id]':'1','data[username]':'<?php $user = wp_get_current_user(); echo $user->display_name;?>'},
			type: "GET",
			dataType: "json",
			success: function (data) {
				console.log("payment page referal link and code at boot init " + data.data.user.spec_word);
				jQuery("#paymentPage_referal_code").text('Your code: ' + data.data.user.spec_word);
				jQuery("#paymentPage_referal_link").text('Your link: http://spin2spin.com/?refer=' + data.data.user.spec_word);
			}
    });	
	
	// payment page withdrawal
	
	jQuery("#paymentPage_withdrawal_send").click(function() {
		
		alert("Your withdrawal request has sended! (address " + jQuery("#paymentPage_withdrawal_address").val() + ', amount ' + jQuery("#paymentPage_withdrawal_amount").val() + ')');
	       
    });
	
	jQuery.ajax({
			url: "https://api.spin2spin.com/api/get/user",
			data: {'data[hall_id]':'1','data[username]':'<?php $user = wp_get_current_user(); echo $user->display_name;?>'},
			type: "GET",
			dataType: "json",
			success: function (data) {
				console.log("payment page deposit adress at boot init " + data.data.address.address);
				jQuery("#paymentPage_deposit_adress_label").text(data.data.address.address);
			}
    });
	
	// payment page credits
	
	jQuery.ajax({
			url: "https://api.spin2spin.com/api/get/user",
			data: {'data[hall_id]':'1','data[username]':'<?php $user = wp_get_current_user(); echo $user->display_name;?>'},
			type: "GET",
			dataType: "json",
			success: function (data) {
				console.log("payment page credits creadits at boot");
				jQuery("#paymentPage_credits_label").text(parseFloat(data.data.user.credits).toFixed(3) + " mBTC");
			}
    });
	
   	jQuery("#paymentPage_credits_refresh").click(function() {
		
		jQuery.ajax({
			url: "https://api.spin2spin.com/api/get/user",
			data: {'data[hall_id]':'1','data[username]':'<?php $user = wp_get_current_user(); echo $user->display_name;?>'},
			type: "GET",
			dataType: "json",
			success: function (data) {
				console.log("payment page credits refresh button click");
				jQuery("#paymentPage_credits_label").text(parseFloat(data.data.user.credits).toFixed(3) + " mBTC");
			}
    	});
	       
    });
	
	// jackpots init at boot

	jQuery.ajax({
        url: "https://api.spin2spin.com/api/get/jackpots",
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            //console.log(res);
            console.log("jackpot 1 at boot = " + res.data[0].jackpot);
			jackPots[0].new = res.data[0].jackpot;
			console.log("jackpot 2 at boot = " + res.data[1].jackpot);
			jackPots[1].new = res.data[1].jackpot;
			console.log("jackpot 3 at boot = " + res.data[2].jackpot);
			jackPots[2].new = res.data[2].jackpot
            //alert(res);
        }
    });
	
	// init top winners
	 
	jQuery.ajax({
        url: "https://api.spin2spin.com/api/get/get_top_winners",
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            console.log("top winner 1 - username " + res.data[0].username + " win " + res.data[0].max_win + " game " + res.data[0].game_name);
			console.log("top winner 2 - username " + res.data[1].username + " win " + res.data[1].max_win + " game " + res.data[1].game_name);
			console.log("top winner 3 - username " + res.data[2].username + " win " + res.data[2].max_win + " game " + res.data[2].game_name);
			console.log("top winner 4 - username " + res.data[3].username + " win " + res.data[3].max_win + " game " + res.data[3].game_name);
			console.log("top winner 5 - username " + res.data[4].username + " win " + res.data[4].max_win + " game " + res.data[4].game_name);
			
			jQuery('#custom_html-4').empty();
			
			jQuery('#custom_html-4').append('<h4 class="widget-title block-title">Max Wins</h4>');
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[0].game_name + ' - ' + res.data[0].max_win + '<br>Winner - ' + res.data[0].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[1].game_name + ' - ' + res.data[1].max_win + '<br>Winner - ' + res.data[1].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[2].game_name + ' - ' + res.data[2].max_win + '<br>Winner - ' + res.data[2].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[3].game_name + ' - ' + res.data[3].max_win + '<br>Winner - ' + res.data[3].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[4].game_name + ' - ' + res.data[4].max_win + '<br>Winner - ' + res.data[4].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
        }
    });
	
	// update top winners
	
	setInterval(function(){
	
	jQuery.ajax({
        url: "https://api.spin2spin.com/api/get/get_top_winners",
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            console.log("top winner 1 - username " + res.data[0].username + " win " + res.data[0].max_win + " game " + res.data[0].game_name);
			console.log("top winner 2 - username " + res.data[1].username + " win " + res.data[1].max_win + " game " + res.data[1].game_name);
			console.log("top winner 3 - username " + res.data[2].username + " win " + res.data[2].max_win + " game " + res.data[2].game_name);
			console.log("top winner 4 - username " + res.data[3].username + " win " + res.data[3].max_win + " game " + res.data[3].game_name);
			console.log("top winner 5 - username " + res.data[4].username + " win " + res.data[4].max_win + " game " + res.data[4].game_name);
			
			jQuery('#custom_html-4').empty();
			
			jQuery('#custom_html-4').append('<h4 class="widget-title block-title">Max Wins</h4>');
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[0].game_name + ' - ' + res.data[0].max_win + '<br>Winner - ' + res.data[0].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[1].game_name + ' - ' + res.data[1].max_win + '<br>Winner - ' + res.data[1].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[2].game_name + ' - ' + res.data[2].max_win + '<br>Winner - ' + res.data[2].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[3].game_name + ' - ' + res.data[3].max_win + '<br>Winner - ' + res.data[3].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[4].game_name + ' - ' + res.data[4].max_win + '<br>Winner - ' + res.data[4].username + '</div>';
						
			jQuery('#custom_html-4').append(elem);
			
        }
    });
		
	}, 20000);
	
	// init last winners
	
	jQuery.ajax({
        url: "https://api.spin2spin.com/api/get/winners",
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            console.log("last winner 1 - username " + res.data[0].username + " win " + res.data[0].win + " game " + res.data[0].game_name);
			console.log("last winner 2 - username " + res.data[1].username + " win " + res.data[1].win + " game " + res.data[1].game_name);
			console.log("last winner 3 - username " + res.data[2].username + " win " + res.data[2].win + " game " + res.data[2].game_name);
			console.log("last winner 4 - username " + res.data[3].username + " win " + res.data[3].win + " game " + res.data[3].game_name);
			console.log("last winner 5 - username " + res.data[4].username + " win " + res.data[4].win + " game " + res.data[4].game_name);
			
			jQuery('#custom_html-2').empty();
			
			jQuery('#custom_html-2').append('<h4 class="widget-title block-title">Last Wins</h4>');
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[0].game_name + ' - ' + res.data[0].win + '<br>Winner - ' + res.data[0].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[1].game_name + ' - ' + res.data[1].win + '<br>Winner - ' + res.data[1].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[2].game_name + ' - ' + res.data[2].win + '<br>Winner - ' + res.data[2].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[3].game_name + ' - ' + res.data[3].win + '<br>Winner - ' + res.data[3].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[4].game_name + ' - ' + res.data[4].win + '<br>Winner - ' + res.data[4].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
        }
    });
	
	// update last winners
	
	setInterval(function(){
	
	jQuery.ajax({
        url: "https://api.spin2spin.com/api/get/winners",
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            console.log("last winner 1 - username " + res.data[0].username + " win " + res.data[0].win + " game " + res.data[0].game_name);
			console.log("last winner 2 - username " + res.data[1].username + " win " + res.data[1].win + " game " + res.data[1].game_name);
			console.log("last winner 3 - username " + res.data[2].username + " win " + res.data[2].win + " game " + res.data[2].game_name);
			console.log("last winner 4 - username " + res.data[3].username + " win " + res.data[3].win + " game " + res.data[3].game_name);
			console.log("last winner 5 - username " + res.data[4].username + " win " + res.data[4].win + " game " + res.data[4].game_name);
			
			jQuery('#custom_html-2').empty();
			
			jQuery('#custom_html-2').append('<h4 class="widget-title block-title">Last Wins</h4>');
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[0].game_name + ' - ' + res.data[0].win + '<br>Winner - ' + res.data[0].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[1].game_name + ' - ' + res.data[1].win + '<br>Winner - ' + res.data[1].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[2].game_name + ' - ' + res.data[2].win + '<br>Winner - ' + res.data[2].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[3].game_name + ' - ' + res.data[3].win + '<br>Winner - ' + res.data[3].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
			elem = '<div class="textwidget custom-html-widget">' + res.data[4].game_name + ' - ' + res.data[4].win + '<br>Winner - ' + res.data[4].username + '</div>';
						
			jQuery('#custom_html-2').append(elem);
			
        }
    });
		
	}, 20000);
	
	// init balance at boot
		
	jQuery.ajax({
        url: "https://api.spin2spin.com/api/get/user",
		data: {'data[hall_id]':'1','data[username]':'<?php $user = wp_get_current_user(); echo $user->display_name;?>'},
        type: "GET",
        dataType: "json",
        success: function (data) {
            console.log("creadits at boot = " + data.data.user.credits);
			jQuery('#credits').text(parseFloat(data.data.user.credits).toFixed(3));
        }
    });
	
});

</script>

</body>
</html>
