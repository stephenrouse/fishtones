<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '15881_fishtones_v3');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'IapI%1K$RSG) Z];m;`-Yaz;VJt*xX2BT~/<.)Q3%DF%TV&92LqDX-|6Uiw|nauo');
define('SECURE_AUTH_KEY',  'ET{?Y6N.m&Gc85?:FRGeF:oYta8F-[8Qm+2HOB .>ONT=/dX|q#j;#IB$v/9(2s*');
define('LOGGED_IN_KEY',    '+7BJwr*N*$+C+8/vgVW[D1;[@sR?]{BHd5r]a|hRED2zn*y_3+aX|{%BaQ[:I(g=');
define('NONCE_KEY',        'ISA38;t?P6%&1v}qU,7jZ-P7P+Z#C9b<.k-HPx@cTaGSN0|*S*M3rCjn8+_IDghf');
define('AUTH_SALT',        '[Xps-~b$!j#Ut}>A&(7w_3^{0/bH=^&-5I4^R2mpc.b^L|h<|PQi#39;]-UU33|/');
define('SECURE_AUTH_SALT', '~M#IzcnoM5`-?|AM]?~%yp#1k#u[LKi[oh}cY`F/|*n]Fi#j{VtKqf(X*3+8L{W;');
define('LOGGED_IN_SALT',   'zW1NqDE{1WX+^[v2/7,@F&3-4Z,O<*Zs=6+v}:~w-e*{V,[~MDdYbz;SyBhA!E$>');
define('NONCE_SALT',       'I+eRcrZ! KY|p8%nN?p4hWz:#da<*8]-J-4+N(+gI{eOo+X_3SUz:>U/!0yZdX%w');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_fishtones_v3_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
// Turns WordPress debugging on
define('WP_DEBUG', true);

// Tells WordPress to log everything to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);

// Doesn't force the PHP 'display_errors' variable to be on
define('WP_DEBUG_DISPLAY', false);

// Hides errors from being displayed on-screen
@ini_set('display_errors', 0);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
