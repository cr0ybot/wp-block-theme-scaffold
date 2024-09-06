<?php
/**
 * Config.php
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS;

use function get_theme_file_path;
use function get_theme_file_uri;

/**
 * Config class.
 *
 * Handles global configuration of the theme.
 */
final class Config {

	/**
	 * Constructor.
	 *
	 * @param string $build_dir Theme build directory. Default 'dist'.
	 * @param string $theme_namespace Theme namespace used for blocks and assets. Default 'wpbts'.
	 */
	public function __construct(
		private string $build_dir = 'dist',
		private string $theme_namespace = 'wpbts',
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
