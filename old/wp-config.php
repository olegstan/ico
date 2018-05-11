<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'u0055426_wp634');

/** MySQL database username */
define('DB_USER', 'u0055426_wp634');

/** MySQL database password */
define('DB_PASSWORD', 'p@aSc87!C5');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '3rlxtxoy4mvcunfo8e6ju8nocq8sjvgh858irglmjlh2jwjrkkiauxfnwp1um0oq');
define('SECURE_AUTH_KEY',  'dkxjpuhm7j8nqeobjsrvaeqxfbsmty30zu7rgiaciwab990izskungkn2ph4rrgo');
define('LOGGED_IN_KEY',    'qelbqhqhvh1q2hzntr0p9qhajwzipydc6ybrkrukhln4qa3tkiivxdfal3este32');
define('NONCE_KEY',        '1706thuwcqysfekplg25zlbjw3ju1xty4sdbm45ylldt1sqf9xuypdbioaviklfw');
define('AUTH_SALT',        'fi93vlje3odjqameijgcexgdw7tm4cvqvg5fxxzabdpepsdsn1hlbves9krwxzyi');
define('SECURE_AUTH_SALT', 'tbmjjniqwcszk3cjwd5o1v9vhejxmwie8djfjm0kme8wwk8m8av5prj1y5e7h94k');
define('LOGGED_IN_SALT',   '1csb1fld3lagkeesqlfkdpew1sy1n14upmdfcndyqd6jt2holvgg76xp0p2fjh7p');
define('NONCE_SALT',       'twtf8kjcwkcdb8drfn59bxncoiaizqhk1ppyky3tnc42ywbekspe9nyiacp3mocw');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpxg_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
