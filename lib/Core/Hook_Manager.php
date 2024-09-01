<?php
/**
 * Core/HookManager.php
 *
 * Inspired by the EventManager class from https://carlalexander.ca/designing-system-wordpress-event-management/
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Core;

use WPBTS\Contracts\Hook_Subscriber;

use function add_filter;
use function remove_filter;

/**
 * Hook Manager class.
 *
 * Manages the registration and unregistration of callbacks for WordPress hooks.
 */
class Hook_Manager {

	/**
	 * Checks if a callback is hooked to a WordPress hook.
	 *
	 * @param string   $name Hook name.
	 * @param callable $callback Callback function.
	 *
	 * @return boolean True if callback is hooked, false otherwise.
	 */
	public function has_callback( string $name, callable $callback ): bool {
		return has_filter( $name, $callback, );
	}

	/**
	 * Gets the current hook name.
	 *
	 * @return string|bool Hook name or false if not currently executing a hook.
	 */
	public function get_current_hook(): string|bool {
		return current_filter();
	}

	/**
	 * Adds a callback to a WordPress hook.
	 *
	 * @uses add_filter
	 *
	 * @param string   $name Hook name.
	 * @param callable $callback Callback function.
	 * @param integer  $priority Priority.
	 * @param integer  $accepted_args Number of accepted arguments.
	 *
	 * @return bool Always returns true.
	 */
	public function add_callback( string $name, callable $callback, int $priority = 10, int $accepted_args = 1 ): bool {
		return add_filter( $name, $callback, $priority, $accepted_args );
	}

	/**
	 * Removes a callback from a WordPress hook.
	 *
	 * @uses remove_filter
	 *
	 * @param string   $name Hook name.
	 * @param callable $callback Callback function.
	 * @param integer  $priority Priority.
	 *
	 * @return bool True if callback was removed, false otherwise.
	 */
	public function remove_callback( string $name, callable $callback, int $priority = 10 ): bool {
		return remove_filter( $name, $callback, $priority );
	}

	/**
	 * Adds all hook subscriptions from a Hook_Subscriber instance.
	 *
	 * @param Hook_Subscriber $subscriber Hook Subscriber instance.
	 */
	public function add_subscribed_hooks( Hook_Subscriber $subscriber ) {
		foreach ( $subscriber->get_subscribed_hooks() as $hook_name => $method_name ) {
			$this->add_subscriber_callback( $subscriber, $hook_name, $method_name );
		}
	}

	/**
	 * Removes all hook subscriptions from a Hook_Subscriber instance.
	 *
	 * @param Hook_Subscriber $subscriber Hook Subscriber instance.
	 */
	public function remove_subscibed_hooks( Hook_Subscriber $subscriber ) {
		foreach ( $subscriber->get_subscribed_hooks() as $hook_name => $method_name ) {
			$this->remove_subscriber_callback( $subscriber, $hook_name, $method_name );
		}
	}

	/**
	 * Adds a hooked callback from a Hook_Subscriber instance.
	 *
	 * @param Hook_Subscriber $subscriber Hook subscriber instance.
	 * @param string          $hook_name Hook name.
	 * @param string|array    $parameters Callback parameters.
	 */
	private function add_subscriber_callback( Hook_Subscriber $subscriber, string $hook_name, string|array $parameters ) {
		if ( is_string( $parameters ) ) {
			$this->add_callback( $hook_name, [ $subscriber, $parameters ] );
		} elseif ( is_array( $parameters ) && count( $parameters ) >= 1 ) {
			$callback      = [ $subscriber, $parameters[0] ];
			$priority      = $parameters[1] ?? 10;
			$accepted_args = $parameters[2] ?? 1;
			$this->add_callback( $hook_name, $callback, $priority, $accepted_args );
		}
	}

	/**
	 * Removes a hooked callback from a Hook_Subscriber instance.
	 *
	 * @param Hook_Subscriber $subscriber Hook Subscriber instance.
	 * @param string          $hook_name Hook name.
	 * @param string|array    $parameters Callback parameters.
	 */
	private function remove_subscriber_callback( Hook_Subscriber $subscriber, string $hook_name, string|array $parameters ) {
		if ( is_string( $parameters ) ) {
			$this->remove_callback( $hook_name, [ $subscriber, $parameters ] );
		} elseif ( is_array( $parameters ) && count( $parameters ) >= 1 ) {
			$callback = [ $subscriber, $parameters[0] ];
			$priority = $parameters[1] ?? 10;
			$this->remove_callback( $hook_name, $callback, $priority );
		}
	}
}
