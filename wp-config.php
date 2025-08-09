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
define( 'DB_NAME', 'aztec' );

/** Database username */
define( 'DB_USER', 'aztec' );

/** Database password */
define( 'DB_PASSWORD', 'password' );

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
define( 'AUTH_KEY',         'Tp^`RjZSnE%6bgftlD80Km9auj&NT@Twjn_JufqNO2@86keh]V6k<jI,DupzXqb$' );
define( 'SECURE_AUTH_KEY',  'j--%uI3y?W*FjWV,`rSj5~u2`KF@HeU[M{uvxv,mc6RAd`^CtfeSo %wyM*2)^$Z' );
define( 'LOGGED_IN_KEY',    '=)j=pWSZ`k3{+={Z/QcvVrneH9y;Nv>Q_>I(lJ%@y-ZAvm|%k_90rQ]-h/r%wkJi' );
define( 'NONCE_KEY',        '[y`YbyR(SKbx@}7@T|%mBUIdBk>xa9!u}Dgm[.tC*[|zTZC1H38&sS:=|CNO:H%6' );
define( 'AUTH_SALT',        '.E^n/ODil.O7nF6LY]Eeuqq# 2.5P]F,e^LC3=jNHgF@1C5=nl6aTqBGS{ypk[f@' );
define( 'SECURE_AUTH_SALT', 'yRSw5W<ij<sYZFo~r`Sq=h`y>$?t?rNn<MY$*{vB;k`%S,gE-x0fME+%P!lk>QL8' );
define( 'LOGGED_IN_SALT',   '=]L*PM:oe.EfJ4.}#4aN62=;PQsF!|IZ^eRDfE?yxmXc08>)RVA$:)de2]nH43w`' );
define( 'NONCE_SALT',       '5bbe)xCA1Ezfyk*R(1$ZUE@&gV1yWa6AE(jvrEXgX`WRtLf0+GkN JRe] YTsK7<' );

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
