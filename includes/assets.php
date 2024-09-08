<?php
/**
 * Include: assets.php
 *
 * Utility functions for registering and enqueuing assets (scripts and styles).
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Assets;

/**
 * Get a script or style asset handle for registration/enqueuing.
 * Prepends 'wpbts-' to the asset name.
 *
 * @param string $name Asset file name without extension.
 */
function get_asset_handle( string $name ) {
	return 'wpbts-' . $name;
}

/**
 * Get js/css asset info from extracted *.asset.php files.
 *
 * @param string  $slug Asset slug, generally the path from the src/scripts or
 *                     src/styles folder.
 * @param ?string $attribute Optional. Attribute to get (e.g. 'dependencies',
 *                          'version').
 *
 * @return string|array Asset info array or specific attribute value.
 */
function get_asset_info(
	string $slug,
	?string $attribute = null,
): string|array|null {
	$js_asset  = get_theme_file_path( '/dist/js/' . $slug . '.asset.php' );
	$css_asset = get_theme_file_path( '/dist/css/' . $slug . '.asset.php' );

	if ( file_exists( $js_asset ) ) {
		$asset = require $js_asset;
	} elseif ( file_exists( $css_asset ) ) {
		$asset = require $css_asset;
	} else {
		return [
			'dependencies' => [],
			'version'      => null,
		];
	}

	if ( ! empty( $attribute ) ) {
		return $asset[ $attribute ] ?? null;
	}

	return $asset;
}

/**
 * Register a built script asset.
 *
 * @param string        $slug Asset slug, generally the path from the
 *                            src/scripts folder excluding the file extension.
 * @param boolean       $enqueue Optional. Whether to enqueue the asset.
 *                               Default false.
 * @param array         $dependencies Optional. Additional dependencies to
 *                                    include, aside from any found in the asset
 *                                    file.
 * @param array|boolean $args Optional. An array of script loading strategies or
 *                            a boolean indicating whether to load the script in
 *                            the footer.
 *                            Default true.
 *
 * @return boolean True if the script was registered successfully, false otherwise.
 */
function register_script_asset(
	string $slug,
	bool $enqueue = false,
	array $dependencies = array(),
	array|bool $args = true,
): bool {
	$asset  = get_asset_info( $slug );
	$handle = get_asset_handle( $slug );

	$registered = wp_register_script(
		$handle,
		get_theme_file_uri( '/dist/js/' . $slug . '.js' ),
		array_merge( $asset['dependencies'], $dependencies ),
		$asset['version'],
		$args
	);

	if ( $enqueue ) {
		wp_enqueue_script( $handle );
	}

	return $registered;
}

/**
 * Register a built style asset.
 *
 * @param string  $slug Asset slug, generally the path from the src/styles
 *                      folder excluding the file extension.
 * @param boolean $enqueue Optional. Whether to enqueue the asset.
 *                         Default false.
 * @param array   $dependencies Optional. Additional dependencies to include,
 *                              aside from any found in the asset file.
 * @param string  $media Optional. Media type for the stylesheet.
 *                       Default 'all'.
 *
 * @return boolean True if the style was registered successfully, false otherwise.
 */
function register_style_asset(
	string $slug,
	bool $enqueue = false,
	array $dependencies = array(),
	string $media = 'all',
): bool {
	$asset  = get_asset_info( $slug );
	$handle = get_asset_handle( $slug );

	$registered = wp_register_style(
		$handle,
		get_theme_file_uri( '/dist/css/' . $slug . '.css' ),
		$dependencies,
		$asset['version'],
		$media
	);

	if ( $enqueue ) {
		wp_enqueue_style( $handle );
	}

	return $registered;
}
