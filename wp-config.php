<?php
//Begin Really Simple Security session cookie settings
@ini_set('session.cookie_httponly', true);
@ini_set('session.cookie_secure', true);
@ini_set('session.use_only_cookies', true);
//END Really Simple Security cookie settings
//Begin Really Simple Security key
define('RSSSL_KEY', 'sS6gPzXNYKCZXAysrtGU4cAIeJJMIFcITb6MjCnXGpyUEZIxYVtUe3CvcWiSptk6');
//END Really Simple Security key

define( 'WP_CACHE', true ); // Added by WP Rocket

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
define( 'DB_NAME', 'wolfprop' );

/** Database username */
define( 'DB_USER', 'wolfprop' );

/** Database password */
define( 'DB_PASSWORD', 'fLTtmGKuGk2z0REtU4MouMBZX' );

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
define( 'AUTH_KEY',         'X+n$I65}`D>_sYZ9uzjPPaRZJH+n~!I;qW/9!d#&][N;zWfyG+ItKVHG8teA0k.P' );
define( 'SECURE_AUTH_KEY',  '2V`Ia@c.O|M5_2@yQ520XZ/,Ym__  $g-M?QiwU,Gczocj|lrNp#t$u2RU{*m2%h' );
define( 'LOGGED_IN_KEY',    'g:x-&(E*~,Qp2KtrWRRQ2JCQ4*o@5!-*Nc,R$$WY5-S-A:!,_OZ3~>lUI;[PN9WD' );
define( 'NONCE_KEY',        '!we/cjHG1!@48=?XN&ZLM4x63sy,}?a&o%D_gWi0a|7t:/U&:66FsDxT{sZA3JT:' );
define( 'AUTH_SALT',        '~M.O6CzC4!Vp0Z)%5{PP`bs[;OG,2?MMkV=.~/)o>L,0/]r4A@*/!*0~hV=zwq %' );
define( 'SECURE_AUTH_SALT', ',UbhE*jZbcT;`b[S~}I00:[oX;FLX~Bg.wh`!,0sfB2U], 8hKkJ~CS1uD}To.^5' );
define( 'LOGGED_IN_SALT',   'A*D5b^dcDa<VdL)x8OewWW]cO^bIVb=Q}vhI4]G!ch?0vre @dl,moIqM>!)>f=o' );
define( 'NONCE_SALT',       'au$R}.sFBaylu65F_#MNaj;.Vka]7UlbI|W9Fz~AV |P).<n[+w@I dx+&8&xQ)V' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'w7rpw_';

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

// define('WP_DEBUG', true);
// define('WP_DEBUG_LOG', true);
// define('WP_DEBUG_DISPLAY', true);

/* Add any custom values between this line and the "stop editing" line. */

define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '256M' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
