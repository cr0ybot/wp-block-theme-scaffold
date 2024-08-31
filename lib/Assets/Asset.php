<?php
/**
 * Asset
 *
 * @package WPBTS
 */

namespace WPBTS\Assets;

use WPBTS\Core;
use WPBTS\Assets\Asset_Type;
use WPBTS\Assets\Asset_Args;

/**
 * Asset class.
 * Handles script and style registration.
 */
class Asset {

	/**
	 * Handle prefix.
	 *
	 * @var string
	 */
	private $handle_prefix = 'wpbts-';

	/**
	 * Asset arguments.
	 *
	 * @var Asset_Args
	 */
	private Asset_Args $args;

	/**
	 * Get asset handle.
	 *
	 * @param string $name Asset name.
	 * @return string
	 */
	public static function get_asset_handle( string $name ) {
		return self::$handle_prefix . $name;
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
	 * @param Asset_Type $type Asset type.
	 * @param array      $dependencies Optional. Asset dependencies. Default empty array.
	 * @param Asset_Args $args Optional. Asset args. Default empty array.
	 */
	public function __construct(
		private string $name,
		private Asset_Type $type,
		private array $dependencies = array(),
		?Asset_Args $args,
	) {
		if ( ! $args ) {
			$args = new Asset_Args( $type );
		}
		$this->args = $args;
	}

	/**
	 * Register asset.
	 *
	 * @param bool $enqueue Optional. Whether to enqueue the asset. Default false.
	 *
	 * @return string|bool Registered asset handle on success, false on failure.
	 */
	public function register( bool $enqueue = false ): string|bool {
		$core       = Core::get_instance();
		$asset_info = self::get_asset_info( $this->name );

		if ( ! $asset_info ) {
			$asset_info = array(
				'version'      => $core->get_theme_version(),
				'dependencies' => array(),
			);
		}

		$handle = self::get_asset_handle( $this->name );
		$src    = $core->get_config()->get_build_url( $this->type . '/' . $this->name . '.' . $this->type->get_extension() );

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

		if ( $enqueue ) {
			if ( $registered ) {
				if ( Asset_Type::script === $this->type ) {
					wp_enqueue_script( $handle );
				} elseif ( Asset_Type::style === $this->type ) {
					wp_enqueue_style( $handle );
				}
			} else {
				return false;
			}
		}

		return $registered ? $handle : false;
	}
}
