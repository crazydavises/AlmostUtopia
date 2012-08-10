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
define('DB_NAME', 'testing_designdavis_com');

/** MySQL database username */
define('DB_USER', 'testingdesigndav');

/** MySQL database password */
define('DB_PASSWORD', 'truelovewaits');

/** MySQL hostname */
define('DB_HOST', 'mysql.testing.designdavis.com');

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
define('AUTH_KEY',         'fVz1w%l@OcxyVnXFTfe3Rq^QTtQGZGC^#asW1:+b(_&h?t8k4pukyPVTc&""ZceX');
define('SECURE_AUTH_KEY',  'lPIQ5;V2LU$9Me3D"wgw;~o8%m+PaEvvZM:zq|4ZDM3&VT*sASn#Q60a!S$ejJtL');
define('LOGGED_IN_KEY',    'kc6);oD3A9QdCay/Llv5dI~vMDW2j1;BtjpFN`BRYmpifhBuKxw^f:#$M5uA:3i7');
define('NONCE_KEY',        '8dQEQZ8QDT*Y)$C(`_`9wn0$WkwuSuh_~L&ZTt2)SJs6l)D/N_JOKfZ#h@Af(3X:');
define('AUTH_SALT',        ';qTF8euJEP@3Ndc_hVc_*X^V_Rfqx"vTV~z+cPKvYw#`T"`yX:Agqu^yERVRpv13');
define('SECURE_AUTH_SALT', 'N(vCTv?iB7wV&`~1A!LL"gru/~5D53P~E?nBDbv(ft%)jss$C2BPFW^7wja?iDoa');
define('LOGGED_IN_SALT',   '7N|"6+sPRqH9W*)f@!JDE$mKzBO3k(*7*E?d$E7dcVFtLEN#&SmMn~RuvR~K`Cjr');
define('NONCE_SALT',       'LC9XF!V`PdGQku;0@A7wEZqu"/EnFJU6zsc(zER/m^/4)T|/KMx:4CfC~T(50*@G');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_ujhmhv_';

/**
 * Limits total Post Revisions saved per Post/Page.
 * Change or comment this line out if you would like to increase or remove the limit.
 */
define('WP_POST_REVISIONS',  10);

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

