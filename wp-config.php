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
define( 'DB_NAME', 'famiprints_net' );

/** MySQL database username */
define( 'DB_USER', 'wpuser' );

/** MySQL database password */
define( 'DB_PASSWORD', 'Hope@123' );

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
define( 'AUTH_KEY',         'xMwmX$;Aj]^xEL>cv<C) ^HBs`Qt4#hiBabde6RP%qPl//<GRYR=S?*AOwu![;bD' );
define( 'SECURE_AUTH_KEY',  '7x;QYE!e}P%E>Oe!q|f?uC12bxZYc%;Gvzp>!f/=Zo#n,*52|@K/<WiH9dF^If;<' );
define( 'LOGGED_IN_KEY',    '>>rZ}!%.FGih#H-_IRv0$<n@^c]MD(zC>t~*9S2S6<F%/ZLBT6B1)r-$@<b#rDY[' );
define( 'NONCE_KEY',        'vg?`VyK:XkyZI4p7Q%;f||CT4CjQtp_T::-F?0HVAI$(~*SykAFfWqY/5QT`DuTt' );
define( 'AUTH_SALT',        'Y(E;NEBk)M-HP*O|`@3b)Zo(2tH}ct1$;S}C->!`aIy}9;;9ieek+y#VL4>6DX-`' );
define( 'SECURE_AUTH_SALT', ';hcCt^,ea|/2E;~[Fn4o(?0[SM6])$/7=K*j9K_$&JwG{NA[/IBn_Gi5-I^>9rh.' );
define( 'LOGGED_IN_SALT',   '~O?1nn:m#s)qWS>wq-5A6*;&o n}vFqfwljhU+iq>L:ODre+{~(yQ*nTw>i(,<pi' );
define( 'NONCE_SALT',       'IqvrFhlr&0Pq %KJ(x!Wv+(Hf>w[~7uaaW1U3DT-[.wt!;yJ7b}^sIOD]1NW=(KI' );

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
