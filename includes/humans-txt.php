<?php
/**
 * Include: humans-txt.php
 *
 * Sets up the humans.txt file to be served from the theme folder.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\HumansTxt;

add_action( 'parse_request', __NAMESPACE__ . '\\render_humans_txt' );
add_action( 'wp_head', __NAMESPACE__ . '\\link_humans_txt', 1 );

/**
 * Check if theme has a humans.txt file.
 *
 * @return boolean True if humans.txt exists, false otherwise.
 */
function theme_has_humans_txt() {
	return file_exists( get_theme_file_path( '/humans.txt' ) );
}

/**
 * Render theme humans.txt at /humans.txt path.
 */
function render_humans_txt() {
	if ( isset( $_SERVER['REQUEST_URI'] ) && $_SERVER['REQUEST_URI'] === '/humans.txt' ) {
		if ( ! theme_has_humans_txt() ) {
			return;
		}

		header( 'Content-Type: text/plain; charset=utf-8' );
		include get_theme_file_path( '/humans.txt' );
		exit();
	}
}

/**
 * Add humans.txt link to head.
 */
function link_humans_txt() {
	if ( ! theme_has_humans_txt() ) {
		return;
	}

	printf(
		'<link rel="author" type="text/plain" href="%s" />',
		esc_url( home_url( 'humans.txt' ) )
	);
}
