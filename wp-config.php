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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bellevue_hospital_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '@VBcO4Sa!7f7GBxCpjEZ6f|46U;Th@HTACtF=>5&}5S[DLTwFF(YfRj`=O91iv1B' );
define( 'SECURE_AUTH_KEY',  '*}zU%du3zcl5SblF2P&DHK}R+WpVCNr K`*>sjc>G*#;IrnUM^XQc9hr$T:42-+(' );
define( 'LOGGED_IN_KEY',    ']oiY!BVds^n3ONc}O?B7X-f@Y2W_0[Z2=HRy:PUp<?1MjF(XqTH )90baAha/hQ5' );
define( 'NONCE_KEY',        '.8/<@.CcsJj?C;#_b2o8:8:5kF,1w=K2tUEc=IMgfna18ll6ZWTglm&a-{m0?)KJ' );
define( 'AUTH_SALT',        '@#F`}Nh4aQ#-6V=:qeB445kc{eytjYvFH&*B<H< 4bcP/>M+q]$GgK5EtHWX7mau' );
define( 'SECURE_AUTH_SALT', ']J2fi4)vhBSJ6 m/+/}G@<oLLf.BqGy5yC9r@1f1*$ NR1-p,I%d+=:Q)CB+qn$)' );
define( 'LOGGED_IN_SALT',   'NL.`/3K@})du|H3c=L%F(pGdk;rud/yOeT<NHp26oZn`TaJ# @9akTonrd%Q|p&L' );
define( 'NONCE_SALT',       'Q6U:w*)ZNun/A;qQ/g*Hg/nn<>rTe P2eW41W+P9x.CD6<QzO{.kLUg6x(Ug8jK0' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
