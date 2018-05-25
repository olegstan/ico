== Install ==

1) create directory "spin2spin-plugin" inside wp-content/plugins directory.
2) upload spin2spon-plugin.php to newly created directory.
3) open WP admin panel and active (custom plugin table wpprefix_spin2spin_user_hashes will be created during activation) plugin Spin2Spin.

== Uninstall ==

1) deactivate plugin in WP admin
2) dump plugin table from database if you need that information and then delete table if needed.
3) delete plugin in WP admin

== How plugin works ==

Plugin when activated monitors hook for new user registration. And when that event appears plugin connects to remote api with username of newly registered user. And gets from it back user hash and external user_id. All that info saves to database custom plugin table (for info about table see Install chapter). Also plugin offers an shortcode (see Shortcodes section of this readme).

== Shortcodes ==

Shortcode 

[spin2spin_plugin_get_link_to_launch_game_sc game_id=9] 

returns link to launch game (use game_id to select game) with hash and external id (if so exists) of current logged user or returns nothing

[spin2spin_plugin_get_link_to_launch_demo_sc game_id=9] 

returns link to launch demo game (use game_id to select game)

[spin2spin_jackpot_plugin_show_jackpots_sc]

returns jackpots
