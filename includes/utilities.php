<?php
/**
 * Include: utilities.php
 *
 * This file contains generic utility functions for use throughout the theme.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Utilities;

/**
 * Output attributes for an HTML tag from an array of key => value pairs. Individual attribute values are escaped.
 *
 * @param array $atts_array Array of key => value pairs.
 * @param bool  $do_echo Whether to echo the attributes or return them.
 *
 * @return string|void The attributes as a string if $do_echo = false.
 */
function html_attrs_from_array( $atts_array = array(), $do_echo = true ) {
	$atts = array_reduce(
		array_keys( $atts_array ),
		function ( $acc, $key ) use ( $atts_array ) {
			$value = $atts_array[ $key ];
			$key   = esc_attr( strtolower( $key ) );
			// Skip null values.
			if ( null === $value ) {
				return $acc;
			}
			// Transform boolean to string.
			if ( is_bool( $value ) ) {
				$value = $value ? 'true' : 'false';
			}
			// Escape values.
			if ( in_array( $key, array( 'href', 'src' ), true ) ) {
				$value = esc_url( $value );
			} else {
				$value = esc_attr( $value );
			}

			$acc[] = $key . '="' . $value . '"';
			return $acc;
		},
		array()
	);

	$atts_string = join( ' ', $atts );

	if ( ! $do_echo ) {
		return $atts_string;
	}

	echo $atts_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Output an HTML style attribute value from an array of key => value pairs. If echoed, the value is escaped.
 *
 * @param array $styles_array Array of key => value pairs.
 * @param bool  $do_echo Whether to echo the style attribute value or return it.
 *
 * @return string|void The style attribute string if $do_echo = false.
 */
function html_style_attr_value_from_array( $styles_array = array(), $do_echo = true ) {
	$styles = array_reduce(
		array_keys( $styles_array ),
		function ( $acc, $key ) use ( $styles_array ) {
			$acc[] = $key . ': ' . $styles_array[ $key ];
			return $acc;
		},
		array()
	);

	$style_string = join( '; ', $styles );

	if ( ! $do_echo ) {
		return $style_string;
	}

	echo esc_attr( $style_string );
}
