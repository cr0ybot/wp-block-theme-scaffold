<?php
/**
 * Contracts/Hook_Subscriber.php
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Hooks;

/**
 * Hook Subscriber interface.
 *
 * Provides an interface for classes that need to hook into WordPress actions
 * and filters.
 */
interface Hook_Subscriber {

	/**
	 * Returns an array of all hooks that the class instance subscribes to.
	 * Override this method in a subclass to add subscribed hooks. Note that
	 * WordPress does not distinguish between actions and filters, so this
	 * method combines both. You can infer whether a callback is for a filter
	 * based on if it returns a value, but it's also a good idea to make note
	 * of this in the method docblock.
	 *
	 * Array keys are action/filter hook names, and values can be on of:
	 * - A string method name to call on the instance
	 * - An array with the method name, optional priority, and optional number of accepted arguments
	 *
	 * Examples:
	 * - ['init' => 'initialize'] - Calls $this->initialize() when the 'init' action is fired
	 * - ['init' => ['initialize', 20]] - Calls $this->initialize() with a priority of 20
	 * - ['init' => ['initialize', 20, 2]] - Calls $this->initialize() with a priority of 20 and 2 accepted arguments
	 *
	 * @return array
	 */
	public function get_subscribed_hooks(): array;
}
