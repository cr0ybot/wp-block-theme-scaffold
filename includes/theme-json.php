<?php
/**
 * Include: theme-json.php
 *
 * Handles dynamic features reliant on the theme.json file.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */
namespace WPBTS\Theme_Json;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\add_vars_to_preset_classes' );

/**
 * Add CSS variables to preset classes.
 *
 * For each color preset class (e.g. .has-{name}-color), add the equivalent
 * value as a locally-scoped CSS variable. This allows us to easily style
 * elements of a block based on the current color and background color settings.
 *
 * @todo Handle other presets (font sizes, gradients, etc.).
 */
function add_vars_to_preset_classes() {
	$settings = \wp_get_global_settings([ 'color' ]);
	error_log( print_r( $settings, true ) );

	$stylesheet = '';

	foreach ( $settings['palette'] as $palette => $colors ) {
		foreach ( $colors as $color ) {
			$stylesheet .= ".has-{$color['slug']}-color { --wpbts-color: var(--wp--preset--color--${$color['slug']}); }\n.has-{$color['slug']}-background-color { --wpbts-background-color: var(--wp--preset--color--{$color['slug']}); }\n\n";
		}
	}

	\wp_register_style( 'wpbts-preset-classes', false );
	\wp_add_inline_style( 'wpbts-preset-classes', $stylesheet );
	\wp_enqueue_style( 'wpbts-preset-classes' );
}
