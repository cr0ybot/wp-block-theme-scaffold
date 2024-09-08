<?php
/**
 * Theme functions file that is autoloaded by WordPress. Used to define
 * constants and load required files. All other functionality should be
 * contained in the includes directory, which is autoloaded.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

// Prevent direct access.
defined( 'ABSPATH' ) || exit;

define( 'WPBTS_THEME_PATH', trailingslashit( get_template_directory() ) );
define( 'WPBTS_THEME_URI', trailingslashit( get_template_directory_uri() ) );
define( 'WPBTS_DIST_PATH', WPBTS_THEME_PATH . 'dist/' );
define( 'WPBTS_DIST_URI', WPBTS_THEME_URI . 'dist/' );
define( 'WPBTS_BLOCKS_PATH', WPBTS_DIST_PATH . 'blocks/' );

/**
 * Automatically require all PHP files in the includes directory.
 * Note that these files should be namespaced and should not use functions
 * from other files except within hook callbacks, since they are loaded
 * in an arbitrary order.
 */
foreach ( glob( __DIR__ . '/includes/*.php' ) as $file ) {
	require_once $file;
}

// Load Composer autoloader if not already loaded.
if ( is_file( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}
