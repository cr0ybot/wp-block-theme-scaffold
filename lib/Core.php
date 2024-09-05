<?php
/**
 * Core.php
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS;

use Exception;
use WPBTS\Config;
use WPBTS\Hooks\Hook_Manager;
use WPBTS\Hooks\Hook_Subscriber;

/**
 * Core theme singleton class.
 *
 * Handles setup of the theme. You can optionally pass a Config instance to the
 * initialize method, otherwise it will use the default config.
 */
final class Core implements Hook_Subscriber {
	/**
	 * Instance.
	 *
	 * @var Core
	 */
	private static $instance;

	/**
	 * Initialize with global config.
	 *
	 * @param Config       $config Optional. Config instance.
	 * @param Hook_Manager $hook_manager Optional. Hook Manager instance.
	 *
	 * @throws Exception If Core is already initialized.
	 */
	public static function initialize(
		Config $config = new Config(),
		Hook_Manager $hook_manager = new Hook_Manager(),
	) {
		if ( isset( self::$instance ) ) {
			throw new Exception( 'Core is already initialized.' );
		}
		self::$instance = new self( $config, $hook_manager );
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
	 * {@inheritDoc}
	 */
	public function get_subscribed_hooks(): array {
		return [
			'after_setup_theme' => 'setup_theme',
		];
	}

	/**
	 * Get theme version.
	 *
	 * Why do so many theme scaffolds use a constant that has to be updated
	 * instead of just getting the version from the theme header?
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
	 * @param Config       $config Config instance.
	 * @param Hook_Manager $hook_manager Hook Manager instance.
	 */
	private function __construct(
		private Config $config,
		private Hook_Manager $hook_manager,
	) {
		$this->hook_manager->add_subscribed_hooks( $this );

		// @todo load modules
	}
}
