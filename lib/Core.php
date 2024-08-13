<?php
/**
 * Core
 *
 * @package WPBTS
 */

namespace WPBTS;

/**
 * Core theme singleton class.
 * Handles global configuration and setup of the theme.
 */
final class Core {
	/**
	 * Instance.
	 *
	 * @var Core
	 */
	private static $instance;

	/**
	 * Initialize with global config.
	 *
	 * @param string $build_dir Build directory. Default 'dist'.
	 *
	 * @throws \Exception If Core is already initialized.
	 */
	public static function initialize(
		string $build_dir = 'dist',
	) {
		if ( isset( self::$instance ) ) {
			throw new \Exception( 'Core is already initialized.' );
		}
		self::$instance = new self( $build_dir );
	}

	/**
	 * Get instance.
	 *
	 * @return Core
	 *
	 * @throws \Exception If Core is not yet initialized.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			throw new \Exception( 'Core is not yet initialized.' );
		}

		return self::$instance;
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

	/**
	 * Get theme version.
	 *
	 * @return string
	 */
	public function get_theme_version() {
		return wp_get_theme()->get( 'Version' );
	}

	/**
	 * Setup theme.
	 */
	public function setup_theme() {
		load_theme_textdomain( 'wpbts', get_template_directory() . '/languages' );
	}

	/**
	 * Constructor.
	 *
	 * @param string $build_dir Build directory.
	 */
	private function __construct(
		private string $build_dir,
	) {
		$this->setup_hooks();
	}

	/**
	 * Setup hooks.
	 */
	private function setup_hooks() {
		add_action( 'after_setup_theme', array( $this, 'setup_theme' ) );
	}
}
