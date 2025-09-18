<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'jasorien_wp_dzzti' );

/** Database username */
define( 'DB_USER', 'jasorien_wp_dpuqn' );

/** Database password */
define( 'DB_PASSWORD', 'T5*5@12QmGyRj65O' );

/** Database hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define('AUTH_KEY', 'U4Mwhfgc#Qr+]!@9u46M+rjj)[l#]m2#7)r-8FF5[06tZP8Tz%!KO7!f!+D;zAR!');
define('SECURE_AUTH_KEY', '_#2667o658%U+(B9)*1E24(04Z2scu2tW(AO7I9J4O&15G7;je@*jQ1u9_uV05D2');
define('LOGGED_IN_KEY', 'j;;s%5071GT760H4Q#8I_6lw6U*~j0_8c0Cz6f#)g4L~BcM|6f*9ju~XZ4LZ8dr6');
define('NONCE_KEY', '5H@rC9sYz*_fFP/4I[Xl9@6#8)K06++!|z9YU54E_4#Mn-R)Eri#OQ2s7k617!+p');
define('AUTH_SALT', '1jc~mWip4bltvs|aPQ]L43ApvxSn4Ku7I2oPM5%:|6xOE~6Wa9Q2V+NL%9LOYf44');
define('SECURE_AUTH_SALT', 'MM_0e~Z]Y~gaQ5-c58]!P/i1&fEyWm!q*cdR@@p2Il5H-j4SMwyl-W[UZW#!qb7-');
define('LOGGED_IN_SALT', '(29aT9MJD(TRa%!dJ38[)4~g48+K65C9(ZAXJ1*b#3TzC6FTDQvf:s*8#Dq0;ufU');
define('NONCE_SALT', '|&]6+#ft%xK0~Dlfy1k0;z_8[R|Ci~s8)~2&5821U8p079gnO@MMZ!iycz+ZLr~w');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'QyNxL_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_ALLOW_MULTISITE', true);
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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
