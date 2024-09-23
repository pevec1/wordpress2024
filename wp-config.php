<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'promtur3' );

/** Database username */
define( 'DB_USER', 'promtur3' );

/** Database password */
define( 'DB_PASSWORD', '12345' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'w<&IjpN^;+k&T&BgQ5`D~vvK~mIu@NzmPx.{atX0Maa|3WC-(hD{320>N?` ?[b+' );
define( 'SECURE_AUTH_KEY',  '^>t0{[B?j,9 0yLvmq:zGB}$E)-%:FT.j8e;4i=gumkmV{|11=b(-kepw6=tHqr+' );
define( 'LOGGED_IN_KEY',    'Il9P9vi]Z~h lpVm3rUuHFgA(w_3v1iY#</@7dTaqZD_Jy?#}uQU)Vzl;fE;#!1x' );
define( 'NONCE_KEY',        '0-|CJk`fn!UEAM2{X&]`M&6QT/ ~8>AMXvhc|l>Ikk;/<}z1`tvPm7a7*OV{bLUd' );
define( 'AUTH_SALT',        'o#F8[@M(Yo=fgbG;te x&,}O.-xk0jHjDJZRI{(gn eM[913}%c%)(B!y;#MBlF&' );
define( 'SECURE_AUTH_SALT', 'ax@rPh%]wocjlIxrlzUGQ+sV}+QEeK`;ieRfv|.TU1J:aul#@{oaN~~X&UHwvt 7' );
define( 'LOGGED_IN_SALT',   '!&D0-syxAt/_y-dG|d5u?/`IXL/4<{:&P-~@9?QdFb<6z nHvT0L=pv.<lKTcdMN' );
define( 'NONCE_SALT',       '986mnxvE(Z-nPS3l1mHxL+E,;DZN2-7*OKL^LWgLHV#H#M;x4)>oBVs$}~@d}9C[' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
