<?php
/**
 * Include: blocks.php
 *
 * Handles registering blocks and loading CSS overrides.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Blocks;

use WPBTS\Assets;

add_action( 'init', __NAMESPACE__ . '\\register_theme_blocks' );
add_action( 'init', __NAMESPACE__ . '\\block_stylesheet_overrides' );
add_action( 'init', __NAMESPACE__ . '\\register_pattern_categories' );

/**
 * Automatically registers all blocks that are located within the custom blocks
 * directory.
 *
 * @return void
 */
function register_theme_blocks() {
	if ( file_exists( WPBTS_BLOCKS_PATH ) ) {
		$block_json_files = glob( WPBTS_BLOCKS_PATH . '*/block.json' );

		foreach ( $block_json_files as $filename ) {
			\register_block_type_from_metadata( $filename );
		}
	}
}

/**
 * Enqueue block-specific style overrides based on folder structure.
 *
 * NOTE: will not pick up on new styles added in a child theme, but will allow
 * overrides of existing block styles in parent theme.
 */
function block_stylesheet_overrides() {
	foreach ( glob( WPBTS_DIST_PATH . 'css/blocks/*', GLOB_ONLYDIR ) as $namespace_dir ) {
		$namespace = basename( $namespace_dir );

		foreach ( glob( $namespace_dir . '/*.css' ) as $block_file ) {
			$blockname            = basename( $block_file, '.css' );
			$asset                = Assets\get_asset_info( 'blocks/' . $namespace . '/' . $blockname );
			$namespaced_blockname = $namespace . '/' . $blockname;
			// @todo Handle editor styles separately.
			// $filename             = is_admin() ? $blockname . '-editor' : $blockname;
			$filename = $blockname;

			// Enqueue the block's style.
			\wp_enqueue_block_style(
				$namespaced_blockname,
				array(
					'handle' => Assets\get_asset_handle( 'block/' . $namespace . '/' . $blockname ),
					'src'    => WPBTS_DIST_URI . 'css/blocks/' . $namespace . '/' . $filename . '.css',
					'path'   => WPBTS_DIST_PATH . 'css/blocks/' . $namespace . '/' . $filename . '.css',
					// Note: Do *not* include asset dependencies, since they come from JS and will cause the styles to not load at all.
					// 'deps'   => $asset['dependencies'],
					'ver'    => $asset['version'],
				),
			);
		}
	}
}

/**
 * Register block pattern categories.
 */
function register_pattern_categories() {
	/**
	 * Filter the theme block pattern categories.
	 *
	 * @param array $pattern_categories Array of block pattern categories.
	 */
	$pattern_categories = apply_filters(
		'wpbts_block_pattern_categories',
		array(
			array(
				'slug'  => 'wpbts',
				'title' => __( 'Theme Patterns', 'wpbts' ),
			),
		)
	);

	foreach ( $pattern_categories as $category ) {
		register_block_pattern_category( $category['slug'], array( 'label' => $category['title'] ) );
	}
}
