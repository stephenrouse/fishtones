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
define('DB_NAME', '15881_fishtones');

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
define('AUTH_KEY',         '7E)+n9p1 n;gph!m1)DX-@hZ#1{Q[7`#K`,s?;WF*.S]mjS;rbyj+6 Zo}) 0-%T');
define('SECURE_AUTH_KEY',  'IE-nqlM*-zKr)k~-J gut!^%x!xD/aPsF^k:/|B]7=W)?B!F,!ASR{I{D#V:r)C6');
define('LOGGED_IN_KEY',    'h}sp}5/P8X}+y8=^H:2{}FHQ9stZUVosEX#@n#xbCDsL#[}H&AJ>lEv.6&}H{BM~');
define('NONCE_KEY',        'Fa(VvQVn:M5U1(o@w9BO+@)H`M*3s)fYL9+_(*O2=1+:6kprX/_4I2&*`ZFyHB_#');
define('AUTH_SALT',        '7~kkA2N`e!,p%kOIR,N!93.+<+I/oaO@OuZd%S#h4Rnm/*UkXSVx_{B~9ae{aBT6');
define('SECURE_AUTH_SALT', 'Q5YV@87tJx*BBH)X`v12si,(B@JALlb(,ZTr4J.tv-x]M+Ac12XHi]Q.!HzD]qO1');
define('LOGGED_IN_SALT',   '=&0J_>QMNziTDi~UC>y|:DPwQO*3LbbuFn_J6k^~&^t `_]xN%2^aAza@~a-M~=j');
define('NONCE_SALT',       ':1AD*.1L|)^c>^k1_PcgFo$tcLJWSLw4P)WMT)0?a^rmd#[{+|*A*)nX*7yH)`Q%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_fishtones_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
