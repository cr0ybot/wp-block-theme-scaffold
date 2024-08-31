<?php
/**
 * Config
 *
 * @package WPBTS
 */

namespace WPBTS;

use get_theme_file_path;
use get_theme_file_uri;

/**
 * Config class.
 * Handles global configuration of the theme.
 */
final class Config {

	/**
	 * Constructor.
	 *
	 * @param string $build_dir Theme build directory. Default 'dist'.
	 */
	public function __construct(
		private string $build_dir = 'dist',
	) {
		// No-op.
	}

	/**
	 * Get config property.
	 *
	 * @param string $name Property name.
	 */
	public function __get( string $name ) {
		return $this->$name;
	}

	/**
	 * Get build file path.
	 *
	 * @param string $build_file_path Path of file relative to build dir.
	 */
	public function get_build_path( string $build_file_path ) {
		return get_theme_file_path( $this->build_dir . '/' . $build_file_path );
	}

	/**
	 * Get build file URL.
	 *
	 * @param string $build_file_path Path of file relative to build dir.
	 */
	public function get_build_url( string $build_file_path ) {
		return get_theme_file_uri( $this->build_dir . '/' . $build_file_path );
	}
}