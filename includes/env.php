<?php
/**
 * Include: env.php
 *
 * Utility functions for environment detection and environment-specific settings.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Env;

use Dotenv\Dotenv;

// Load environment variables from .env file.
$dotenv = Dotenv::createImmutable( get_theme_file_path() );
$dotenv->safeLoad();

/**
 * Get an environment variable.
 *
 * @param string $key The environment variable key.
 *
 * @return string|null The environment variable value, or null if not set.
 */
function get_env_var( string $key ): ?string {
	return $_ENV[ $key ] ?? null; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
}

/**
 * Get the current environment type, taking FORCE_ENV into account.
 *
 * @return string The environment type.
 */
function get_environment(): string {
	// If FORCE_ENV is set, return that.
	$force_env = get_env_var( 'FORCE_ENV' );
	if ( $force_env ) {
		return $force_env;
	}

	// Otherwise, return the environment type.
	return wp_get_environment_type();
}

/**
 * Check if the current environment is local.
 *
 * @return boolean True if the environment is local, false otherwise.
 */
function is_local() {
	// First, check if FORCE_ENV is set to 'local'.
	if ( in_array( get_env_var( 'FORCE_ENV' ), [ 'local', 'development' ], true ) ) {
		return true;
	}

	$is_local_env = in_array( wp_get_environment_type(), array( 'local', 'development' ), true );
	$is_local_url = strpos( home_url(), '.test' ) || strpos( home_url(), '.local' );
	return $is_local_env || $is_local_url;
}

/**
 * Check if the current environment is staging.
 *
 * @return boolean True if the environment is staging, false otherwise.
 */
function is_staging() {
	// First, check if FORCE_ENV is set to 'staging'.
	if ( get_env_var( 'FORCE_ENV' ) === 'staging' ) {
		return true;
	}

	return wp_get_environment_type() === 'staging';
}

/**
 * Check if the current environment is production.
 *
 * @return boolean True if the environment is production, false otherwise.
 */
function is_production() {
	// First, check if FORCE_ENV is set to 'production'.
	if ( get_env_var( 'FORCE_ENV' ) === 'production' ) {
		return true;
	}

	return wp_get_environment_type() === 'production';
}
