<?php
/*
Plugin Name:  Spin2Spin Plugin
Plugin URI:   https://spin2spin.com
Description:  Spin2Spin Plugin
Version:      0.2.0
Author:       spin2spin.com
Author URI:   https://spin2spin.com
License:      Proprietary
License URI:  https://spin2spin.com
*/

/*

Copyright 2017 spin2spin.com 
   
*/

// create table for plugin with user hashes and external user id activation hook

register_activation_hook( __FILE__, 'spin2spin_plugin_create_db' );

function spin2spin_plugin_create_db() {
	
	global $wpdb;
	
	$charset_collate = $wpdb->get_charset_collate();
	
	$table_name = $wpdb->prefix . 'spin2spin_users_hashes';

	$sql = "CREATE TABLE $table_name (
		id int UNSIGNED NOT NULL AUTO_INCREMENT,
		user_id bigint(20) UNSIGNED NOT NULL,
		hash varchar(10) NOT NULL,
		external_user_id int UNSIGNED NOT NULL,
		created_at datetime NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

// register assets on init hook

add_action('init', 'spin2spin_jackpot_plugin_register_script');

function spin2spin_jackpot_plugin_register_script() {
    wp_register_script( 'spin2spin_jackpot_plugin_jquery', plugins_url('/assets/js/jquery.min.js', __FILE__));
    wp_register_script( 'spin2spin_jackpot_plugin_socket', plugins_url('/assets/js/socket.js', __FILE__));
    wp_register_script( 'spin2spin_jackpot_plugin_jackpot', plugins_url('/assets/js/jackpot.js', __FILE__));

    wp_register_style( 'spin2spin_jackpot_plugin_lobby', plugins_url('/assets/css/lobby.css', __FILE__));
}

// load assets hook

add_action('wp_enqueue_scripts', 'spin2spin_jackpot_plugin_enqueue_style');

function spin2spin_jackpot_plugin_enqueue_style(){
   wp_enqueue_script('spin2spin_jackpot_plugin_jquery');
   wp_enqueue_script('spin2spin_jackpot_plugin_socket');
   wp_enqueue_script('spin2spin_jackpot_plugin_jackpot');

   wp_enqueue_style( 'spin2spin_jackpot_plugin_lobby' );
}

// register user on remote api and get hash and external user id for that user hook

add_action( 'user_register', 'spin2spin_plugin_get_hash_for_new_user');

function spin2spin_plugin_get_hash_for_new_user( $user_id ) {
	
	$user_object = get_user_by('id', $user_id);

	$user_login = $user_object->user_login;
	
	//
	
	$remote_api_hash = 'lHzuDj7p2D';

	$data = [
		'language_id' => '2',
		'hall_id' => '1',
		'username' => $user_login
	];

	$remote_api_hash = password_hash($remote_api_hash . json_encode($data), PASSWORD_BCRYPT);
	
	$url = 'http://api.spin2spin.com/api/store/user';  
	
    $post_data = [
		'data' => $data,
		'hash' => $remote_api_hash
	];

    $response = wp_remote_post( $url, array( 
		'body' => $post_data
    ));
    
    if ( is_wp_error( $response ) ) {
		
		$error_message = $response->get_error_message();
		
		error_log("Error in respnonse from remote api", 0);
		
		
	} else {

		$response_body = wp_remote_retrieve_body( $response );

		$response_body_array = json_decode($response_body, true);

		$user_hash = $response_body_array['data']['user']['hash'];

		$external_user_id = $response_body_array['data']['user']['id'];

		global $wpdb;

		$table_name = $wpdb->prefix . 'spin2spin_users_hashes';

		$wpdb->insert( 
			$table_name, 
			array( 
				'user_id' => $user_id, 
				'hash' => $user_hash, 
				'external_user_id' => $external_user_id,
				'created_at' => current_time( 'mysql' ) 
			) 
		);
	   
	}  

}

// get current user hash shortcode

add_shortcode( 'spin2spin_plugin_get_current_user_hash_sc', 'spin2spin_plugin_get_current_user_hash');

function spin2spin_plugin_get_current_user_hash(){
	
	if ( is_user_logged_in() ){

		$current_user = wp_get_current_user();
		
		$current_user_id = $current_user->ID;
		
		global $wpdb;

		$table_name = $wpdb->prefix . 'spin2spin_users_hashes';
		
		$sql = $wpdb->prepare( "SELECT hash FROM " . $table_name . " where user_id=%s", $current_user_id);
		
		$results = $wpdb->get_row($sql);
		
		if ($results == null){
		
			return "null";
			
		} else {
		
			return $results->hash;
			
		}
		
	} else {
	
		return "demo";
		
	}
		
}

// get current user external id shortcode

add_shortcode( 'spin2spin_plugin_get_current_user_external_user_id_sc', 'spin2spin_plugin_get_current_user_external_user_id');

function spin2spin_plugin_get_current_user_external_user_id(){
	
	if ( is_user_logged_in() ){

		$current_user = wp_get_current_user();
		
		$current_user_id = $current_user->ID;
		
		global $wpdb;

		$table_name = $wpdb->prefix . 'spin2spin_users_hashes';
		
		$sql = $wpdb->prepare( "SELECT external_user_id FROM " . $table_name . " where user_id=%s", $current_user_id);
		
		$results = $wpdb->get_row($sql);
		
		if ($results == null){
		
			return "null";
			
		} else {
		
			return $results->external_user_id;
			
		}
		
	} else {
	
		return "demo";
		
	}
	
}

// get link to launch game shortcode

add_shortcode( 'spin2spin_plugin_get_link_to_launch_game_sc', 'spin2spin_plugin_get_link_to_launch_game');

function spin2spin_plugin_get_link_to_launch_game( $atts ){
	
	$hash = spin2spin_plugin_get_current_user_hash();
	$external_user_id = spin2spin_plugin_get_current_user_external_user_id();
	
	$game_id = $atts['game_id'];
	
	if ($hash != 'null' && $hash != 'demo' && $external_user_id != 'null' && $external_user_id!='demo'){
	
		return "<a class=\"btn  btn-lg btn-success\" onclick=\"window.open('http://api.spin2spin.com/api/get/game?hash=1lHzuDj7p2D&data[hall_id]=1&data[user_id]=" . $external_user_id . "&data[user_hash]=" . $hash . "&data[game_id]=" . $game_id . "&data[game_type]=1', '_blank',  'scrollbars=no,fullscreen=yes,width=1280,height=720')\">Играть</a>";
		
	}
	
}

// get link to launch demo shortcode

add_shortcode( 'spin2spin_plugin_get_link_to_launch_demo_sc', 'spin2spin_plugin_get_link_to_launch_demo');

function spin2spin_plugin_get_link_to_launch_demo( $atts ){
	
	$game_id = $atts['game_id'];
	
	return "<a class=\"btn  btn-lg btn-success\" onclick=\"window.open('https://api.spin2spin.com/demo/v1/" . $game_id. "', '_blank',  'scrollbars=no,fullscreen=yes,width=1280,height=720')\">Демо</a>";
		
}

// get jackpots meta

add_shortcode( 'spin2spin_jackpot_plugin_show_jackpots_meta_sc', 'spin2spin_jackpot_plugin_show_jackpots_meta');

function spin2spin_jackpot_plugin_show_jackpots_meta(){
	
$jackpots_meta = "";

$jackpots_meta  .= '<script>';
$jackpots_meta  .= 'window.wsHost = "api.spin2spin.com";';
$jackpots_meta  .= 'window.wsPort = "443";';
$jackpots_meta  .= 'window.isAuth = "false";';
$jackpots_meta  .= 'window.userHash = "' . spin2spin_plugin_get_current_user_hash() . '";';
$jackpots_meta  .= 'window.userHallId = "lHzuDj7p2D";';
$jackpots_meta  .= 'window.redirectHost = "http://google.ru";';
$jackpots_meta  .= 'window.openedGamesArr = [];';
$jackpots_meta  .= 'window.demoEnabled = true;';
$jackpots_meta  .= '</script>';

return $jackpots_meta;
		
}

// show jackopts shortcode

add_shortcode( 'spin2spin_jackpot_plugin_show_jackpots_sc', 'spin2spin_jackpot_plugin_show_jackpots');

function spin2spin_jackpot_plugin_show_jackpots(){
	
$jackpots .= <<<JACKPOTS

<div class="jackpots_div">
<section class="jackpots">
	<section style="display: none" class="jackpot" id="jackpot_gold">
		<section class="jackpot-name">
			<section class="jackpot-name-text">
				Gold Jackpot
			</section>
		</section>
		<section class="jackpot-value">

			<section class="jackpot-value-group">
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
			<section class="jackpot-value-group">
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
			<section class="jackpot-value-group-decimal">
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-dia">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
		</section>
	</section>
	<section style="display: none" class="jackpot" id="jackpot_silver">
		<section class="jackpot-name">
			<section class="jackpot-name-text">
				Silver jackpot
			</section>
		</section>
		<section class="jackpot-value">

			<section class="jackpot-value-group">
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
			<section class="jackpot-value-group">
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
			<section class="jackpot-value-group-decimal">
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-pla">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
		</section>
	</section>
	<section style="display: none" class="jackpot" id="jackpot_bronze">
		<section class="jackpot-name">
			<section class="jackpot-name-text">
				Bronze jackpot
			</section>
		</section>
		<section class="jackpot-value">

			<section class="jackpot-value-group">
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
			<section class="jackpot-value-group">
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
			<section class="jackpot-value-group-decimal">
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
				<section class="jackpot-digit jackpot-gol">
					<section class="digits-drum">
						<section class="digit">0</section>
						<section class="digit">1</section>
						<section class="digit">2</section>
						<section class="digit">3</section>
						<section class="digit">4</section>
						<section class="digit">5</section>
						<section class="digit">6</section>
						<section class="digit">7</section>
						<section class="digit">8</section>
						<section class="digit">9</section>
					</section>
				</section>
			</section>
		</section>
	</section>
</section>
</div>

<script>
    jQuery(document).ready(function($){
        //resizeContent();

        var jackpot_names = ['jackpot_gold', 'jackpot_silver', 'jackpot_bronze'];
        var jackpot_values = [];
        
		jackpot_values.push(127.6006250);
		jackpot_values.push(200.0000000);
		jackpot_values.push(300.0000000);
		
        for(var i = 0; i < jackpot_values.length; ++i){
			
            $('#' + jackpot_names[i]).show();
            
            setJackPot(i, jackpot_values[i]);
            
        }
        
    });
</script>

JACKPOTS;
	
return $jackpots;
		
}

// show balance shortcode

add_shortcode( 'spin2spin_jackpot_plugin_show_balance_sc', 'spin2spin_jackpot_plugin_show_balance');

function spin2spin_jackpot_plugin_show_balance(){

	$balance = '';
	
	$balance .= '<span class="balance" id="credits">';
	$balance .= '0';
	$balance .= '</span>';

	return $balance;

}
