<?php
/**
 * Include: setup.php
 *
 * Sets up the theme, including registering assets, menus, and other theme features.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Setup;

use WPBTS\Assets;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\theme_support' );
add_action( 'init', __NAMESPACE__ . '\\register_assets' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_frontend_assets' );
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_editor_assets' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_assets' );

/**
 * Register theme support & textdomain.
 */
function theme_support() {
	/**
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 */
	load_theme_textdomain( 'ap-theme', get_template_directory() . '/languages' );

	/**
	 * The following theme support is automatically enabled in block themes:
	 * - post-thumbnails
	 * - editor-styles
	 * - responsive-embeds
	 * - automatic-feed-links
	 * - html5 styles
	 * - html5 scripts
	 *
	 * @see https://fullsiteediting.com/lessons/creating-block-based-themes/#h-theme-support
	 */

	// Add editor styles. Note: theme support is automatically enabled in block themes.
	add_editor_style( 'dist/css/editor.css' );

	// Include opinionated core block styles. This is not recommended.
	// add_theme_support( 'wp-block-styles' );

	// Remove core block patterns if you're providing your own in the patterns directory.
	remove_theme_support( 'core-block-patterns' );
}

/**
 * Register scripts and styles.
 *
 * This is for any script/stype that needs to be available for conditional
 * enqueuing, such as a script that is enqueued on both the frontend and in the
 * editor, or a script that is only needed as a dependency for another script.
 */
function register_assets() {
	// Register script/style assets to enqueue later.
}

/**
 * Enqueue frontend scripts and styles.
 */
function enqueue_frontend_assets() {
	Assets\register_style_asset( slug: 'frontend', enqueue: true );
	Assets\register_script_asset( slug: 'theme', enqueue: true );
}

/**
 * Enqueue editor scripts and styles.
 *
 * Note that the main editor stylesheet is already enqueued via
 * add_editor_style() in theme_support(), which processes the styles and
 * includes the .editor-styles-wrapper scope for higher specificity.
 *
 * If you need to enqueue additional styles that do not need to be scoped to
 * the .editor-styles-wrapper (as well as additional scripts), you can do so here.
 */
function enqueue_editor_assets() {
	Assets\register_script_asset( slug: 'editor', enqueue: true );
}

/**
 * Enqueue admin scripts and styles.
 */
function enqueue_admin_assets() {
	Assets\register_style_asset( slug: 'admin', enqueue: true );
}
