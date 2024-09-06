<?php
/**
 * Assets/Asset.php
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package WPBTS
 */

namespace WPBTS\Assets;

use WPBTS\Core;

/**
 * Asset class.
 *
 * Handles script and style registration.
 */
class Asset {

	/**
	 * Asset arguments.
	 *
	 * @var Asset_Args
	 */
	private Asset_Args $args;

	/**
	 * Whether the asset has been registered.
	 *
	 * @var boolean
	 */
	private $registered = false;

	/**
	 * Get asset handle prefix.
	 */
	public static function get_asset_handle_prefix() {
		$config = Core::get_instance()->get_config();
		return $config->theme_namespace . '-';
	}

	/**
	 * Format asset handle.
	 *
	 * @param string $name Asset name.
	 * @return string
	 */
	public static function format_asset_handle( string $name ) {
		return self::get_asset_handle_prefix() . $name;
	}

	/**
	 * Get asset file info.
	 *
	 * @param string $name Asset name.
	 * @param string $attribute Optional. Asset attribute to fetch instead of the whole asset info.
	 */
	public static function get_asset_info( string $name, string $attribute = null ) {
		$config = Core::get_instance()->get_config();
		if ( file_exists( $config->get_build_path( 'js/' . $name . '.asset.php' ) ) ) {
			$asset = require $config->get_build_path( 'js/' . $name . '.asset.php' );
		} elseif ( file_exists( $config->get_build_path( 'css/' . $name . '.asset.php' ) ) ) {
			$asset = require $config->get_build_path( 'css/' . $name . '.asset.php' );
		} else {
			return null;
		}

		if ( ! empty( $attribute ) && isset( $asset[ $attribute ] ) ) {
			return $asset[ $attribute ];
		}

		return $asset;
	}

	/**
	 * Constructor.
	 *
	 * @param string     $name Asset name.
	 * @param Asset_Type $type Optional. Asset type. Default script.
	 * @param ?string    $src Optional. Asset source URL, generally for external assets. Default null, will use build URL.
	 * @param ?string    $version Optional. Asset version. Default null, will use version from asset file or theme version.
	 * @param array      $dependencies Optional. Asset dependencies. Default empty array.
	 * @param Asset_Args $args Optional. Asset args. Default empty array.
	 * @param string     $enqueue_hook Optional. Enqueue hook. Default 'wp_enqueue_scripts'.
	 * @param int        $priority Optional. Enqueue priority. Default 10.
	 * @param callable   $enqueue_condition Optional. Enqueue condition. Default null.
	 */
	public function __construct(
		private string $name,
		private Asset_Type $type = Asset_Type::script,
		private ?string $src = null,
		private ?string $version = null,
		private array $dependencies = array(),
		?Asset_Args $args,
		private string $enqueue_hook = 'wp_enqueue_scripts',
		private int $priority = 10,
		private callable $enqueue_condition = null
	) {
		if ( ! $args ) {
			$args = new Asset_Args( $type );
		}
		$this->args = $args;
	}

	/**
	 * Get asset handle.
	 *
	 * @return string Formatted asset handle.
	 */
	public function get_handle(): string {
		return self::format_asset_handle( $this->name );
	}

	/**
	 * Register asset.
	 *
	 * @return string|false Registered asset handle on success, false on failure.
	 */
	public function register(): string|false {
		$core       = Core::get_instance();
		$handle     = $this->get_handle();
		$src        = $this->src;
		$asset_info = null;

		if ( $src === null ) {
			$asset_info = self::get_asset_info( $this->name );
			$src        = $core->get_config()->get_build_url( $this->type . '/' . $this->name . '.' . $this->type->get_extension() );
		}

		if ( ! $asset_info ) {
			$asset_info = array(
				'version'      => $this->version ?? $core->get_theme_version(),
				'dependencies' => array(),
			);
		}

		$registered = false;
		if ( Asset_Type::script === $this->type ) {
			$registered = wp_register_script(
				$handle,
				$src,
				array_merge( $this->dependencies, $asset_info['dependencies'] ),
				$asset_info['version'],
				$this->args->get_args()
			);
		} elseif ( Asset_Type::style === $this->type ) {
			$registered = wp_register_style(
				$handle,
				$src,
				$this->dependencies, // Do not include asset_info['dependencies'] for styles.
				$asset_info['version'],
				$this->args->get_args()
			);
		}

		$this->registered = $registered;

		return $registered ? $handle : false;
	}

	/**
	 * Enqueue asset.
	 *
	 * @return string|bool Registered asset handle on success, false on failure.
	 */
	public function enqueue(): string|bool {
		if ( ! $this->is_enqueue_condition_met() ) {
			return false;
		}

		if ( ! $this->registered ) {
			$this->register();
		}

		$handle = $this->get_handle();

		if ( Asset_Type::script === $this->type ) {
			wp_enqueue_script( $handle );
		} elseif ( Asset_Type::style === $this->type ) {
			wp_enqueue_style( $handle );
		}

		return $handle;
	}

	/**
	 * Check if the condition is met.
	 *
	 * @return bool
	 */
	private function is_enqueue_condition_met(): bool {
		return $this->enqueue_condition ? call_user_func( $this->enqueue_condition, $this ) : true;
	}
}
