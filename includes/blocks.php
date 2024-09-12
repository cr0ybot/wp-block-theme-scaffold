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
 * Automatically registers all blocks that are located within the includes / blocks directory
 *
 * @return void
 */
function register_theme_blocks() {
	global $pagenow;

	// Register all the blocks in the theme.
	if ( file_exists( WPBTS_BLOCKS_PATH ) ) {
		$block_json_files = glob( WPBTS_BLOCKS_PATH . '*/block.json' );

		/**
		 * Filter the block allowlist. This is an array of block names and the
		 * post types they should be allowed on. If a block is not in this list,
		 * it will be allowed on all post types. Note that adding a block name
		 * with an empty array will prevent it from being registered on all post
		 * types.
		 *
		 * If you want to allow a block only on posts and pages, you would return:
		 *
		 * ```
		 * array(
		 *  'wpbts/example-block' => array( 'post', 'page' ),
		 * );
		 * ```
		 *
		 * @param array $block_allowlist The block allowlist.
		 */
		$block_allowlist = apply_filters( 'wpbts_block_allowlist', array() );

		// Get the current post type.
		$typenow = false;
		if ( is_admin() ) {
			// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			// phpcs:disable WordPress.Security.NonceVerification
			if ( 'post-new.php' === $pagenow ) {
				if ( isset( $_REQUEST['post_type'] ) && post_type_exists( wp_unslash( $_REQUEST['post_type'] ) ) ) {
					$typenow = wp_unslash( $_REQUEST['post_type'] );
				}
			} elseif ( 'post.php' === $pagenow ) {
				// phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf
				if ( isset( $_GET['post'] ) && isset( $_POST['post_ID'] ) && (int) $_GET['post'] !== (int) $_POST['post_ID'] ) {
					// Do nothing.
				} elseif ( isset( $_GET['post'] ) ) {
					$post_id = (int) $_GET['post'];
				} elseif ( isset( $_POST['post_ID'] ) ) {
					$post_id = (int) $_POST['post_ID'];
				}
				if ( $post_id ) {
					$post    = get_post( $post_id );
					$typenow = $post->post_type;
				}
			}
			// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			// phpcs:enable WordPress.Security.NonceVerification
		}

		// Auto-register all blocks that were found.
		foreach ( $block_json_files as $filename ) {
			$block_folder = dirname( $filename );
			$metadata     = \wp_json_file_decode( $filename, array( 'associative' => true ) );

			if ( $typenow && ! empty( $block_allowlist ) && array_key_exists( $metadata['name'], $block_allowlist ) ) {
				// If the block is in the allowlist, but the current post type is not in the allowlist, skip it.
				if ( ! in_array( $typenow, $block_allowlist[ $metadata['name'] ], true ) ) {
					continue;
				}
			}

			$block_options    = array();
			$render_file_path = $block_folder . '/render.php';

			if ( file_exists( $render_file_path ) ) {

				// Only add `render_callback` if `render` is not defined or if there is no `acf.renderTemplate` registered.
				if ( ! isset( $metadata['render'] ) && ( ! $metadata['acf']['renderTemplate'] ?? false ) ) {

					// Only add the render callback if the block folder has a file called render.php.
					$block_options['render_callback'] = function ( $attributes, $content, $block ) use ( $render_file_path ) {

						// Create helpful variables aside from $attributes, $content, and $block that will be accessible in the render.php file.
						$context = $block->context;

						// Get the output from the render.php file.
						ob_start();
						include $render_file_path;
						return ob_get_clean();
					};
				}
			}

			\register_block_type_from_metadata( $block_folder, $block_options );
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
			//$filename             = is_admin() ? $blockname . '-editor' : $blockname;
			$filename             = $blockname;

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
