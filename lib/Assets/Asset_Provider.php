<?php
/**
 * Assets/Asset_Provider.php
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Assets;

/**
 * Asset Provider interface.
 *
 * Classes that implement this interface can be used to provide assets to the Asset Manager.
 */
interface Asset_Provider {
	/**
	 * Returns an array of all assets to be registered/enqueued by the provider.
	 * Override this method in a subclass to add managed assets. The array
	 * can contain either Asset instances, arrays of asset arguments, or a mix
	 * of both.
	 *
	 * When using the array format, the array should contain the following keys:
	 * - 'type' (required) - 'script' or 'style'
	 * - 'name' (required) - The asset name, used to generate the handle
	 * - 'src' (optional) - The asset source URL or null for local assets
	 * - 'deps' (optional) - Array of asset names this asset depends on
	 * - 'ver' (optional) - Asset version
	 * - 'args' (optional) - Array of additional arguments to pass to wp_register_script/style
	 * - 'enqueue_hook' (optional) - The hook to use for enqueuing the asset
	 * - 'priority' (optional) - The priority to use for enqueuing the asset
	 * - 'condition' (optional) - A callable that returns a boolean to determine if the asset should be enqueued
	 *
	 * @return array
	 */
	public function get_assets(): array;
}
