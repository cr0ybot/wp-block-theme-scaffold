<?php
/**
 * Core
 *
 * @package WPBTS
 */

namespace WPBTS;

use Exception;
use WPBTS\Config;

/**
 * Core theme singleton class.
 * Handles setup of the theme.
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
	 * @param Config $config Optional. Config instance.
	 *
	 * @throws Exception If Core is already initialized.
	 */
	public static function initialize(
		Config $config = null,
	) {
		if ( isset( self::$instance ) ) {
			throw new Exception( 'Core is already initialized.' );
		}
		self::$instance = new self( $config );
	}

	/**
	 * Get instance.
	 *
	 * @return Core
	 *
	 * @throws Exception If Core is not yet initialized.
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			throw new Exception( 'Core is not yet initialized.' );
		}

		return self::$instance;
	}

	/**
	 * Get config.
	 *
	 * @return Config
	 */
	public function get_config() {
		return $this->config;
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
	 * @param Config $config Config instance.
	 */
	private function __construct(
		private Config $config,
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
