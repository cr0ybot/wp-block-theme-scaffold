<?php
/**
 * Theme functions file that is autoloaded by WordPress. Used to load required
 * files and initialize the theme.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

use WPBTS\Core;

// Prevent direct access.
defined( 'ABSPATH' ) || exit;

// Load Composer autoloader if not already loaded.
if ( ! class_exists( Core::class ) && is_file( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}

// Initialize the core theme class.
Core::initialize();
