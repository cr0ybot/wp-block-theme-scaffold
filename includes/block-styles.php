<?php
/**
 * Include: block-styles.php
 *
 * Registers custom block styles and their stylesheets.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Block_Styles;

use WPBTS\Assets;

add_action( 'init', __NAMESPACE__ . '\\register_stylesheets' );

/**
 * Get the path for a block style's stylesheet.
 *
 * @param string $name Block style name.
 * @return string The block style path.
 */
function get_block_style_path( string $name ): string {
	return 'block-styles/' . $name;
}

/**
 * Get the registered handle for a block style's stylesheet.
 *
 * @param string $name Block style name.
 * @return string The registered block style handle (with theme prefix).
 */
function get_block_style_handle( string $name ): string {
	return Assets\get_asset_handle( get_block_style_path( $name ) );
}

/**
 * Get the header definitions for a block style file.
 *
 * @return array Header definitions.
 */
function get_block_style_header_definitions(): array {
	return [
		'label'       => 'Title',
		'name'        => 'Slug',
		'block_name'  => 'Block Types',
		'is_default'  => 'Is Default',
		'description' => 'Description',
	];
}

/**
 * Register all stylesheets in the /dist/css/block-styles directory.
 *
 * These can be enqueued via the `register_block_style()`'s
 * `$style_properties['style_handle']` parameter.
 */
function register_stylesheets(): void {
	$stylesheets = glob( WPBTS_DIST_PATH . 'css/block-styles/*.css' );

	foreach ( $stylesheets as $stylesheet ) {
		Assets\register_style_asset(
			get_block_style_path( pathinfo( $stylesheet, PATHINFO_FILENAME ) )
		);

		// Automatically register block styles if the file has a file header.
		register_block_style_from_file_headers( $stylesheet );
	}
}

/**
 * Register a block style from a file's header.
 *
 * If the file has a file header, the block style will be automatically
 * registered with these parameters:
 *
 * Title: Required. The block style label. Ex: 'Large Text'.
 * Slug: The block style name. Ex: 'large-text'. Defaults to the file name.
 * Block Types: Required. List of blocks the style applies to. Ex: 'core/paragraph, core/heading'.
 * Is Default: Optional. Whether the style is the default for the block. Default: false.
 * Description: Optional. A description of the style.
 *
 * @param string $file Path to the file.
 * @return boolean True if the block style was registered, false if not.
 */
function register_block_style_from_file_headers( string $file ): bool {
	$headers = get_file_data(
		$file,
		get_block_style_header_definitions(),
		'block-styles'
	);

	if ( empty( $headers['label'] ) || empty( $headers['block_name'] ) ) {
		return false;
	}

	$filename         = pathinfo( $file, PATHINFO_FILENAME );
	$block_names      = array_map( 'trim', explode( ',', $headers['block_name'] ) );
	$style_properties = [
		'name'         => $headers['name'] ?? $filename,
		'label'        => $headers['label'],
		'is_default'   => ! empty( $headers['is_default'] ) && strtolower( $headers['is_default'] ) === 'true',
		'style_handle' => get_block_style_handle( $filename ),
	];

	/**
	 * Block styles are not enqueued automatically on the front end like they
	 * are in the editor.
	 * @see https://github.com/WordPress/gutenberg/issues/27244
	 */
	if ( ! is_admin() ) {
		foreach ( $block_names as $block_name ) {
			wp_enqueue_block_style(
				$block_name,
				[
					'handle' => $style_properties['style_handle'],
					'src'    => get_theme_file_uri( '/dist/css/block-styles/' . $filename . '.css' ),
					'path'   => $file,
					'ver'	=> Assets\get_asset_info( get_block_style_path( $filename ) )['version'],
				]
			);
		}
	}

	return register_block_style(
		$block_names,
		$style_properties,
	);
}
