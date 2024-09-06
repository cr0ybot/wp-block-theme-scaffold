<?php
/**
 * Assets/Asset_Manager.php
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Assets;

use WPBTS\Hooks\Hook_Manager;

/**
 * Asset Manager.
 *
 * Manages the registration & enqueuing of assets.
 */
class Asset_Manager {

	/**
	 * Constructor.
	 *
	 * @param Hook_Manager $hook_manager Hook Manager instance.
	 */
	public function construct( private Hook_Manager $hook_manager ) {}

	// public function add_asset()
}
