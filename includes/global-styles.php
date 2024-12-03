<?php
/**
 * Include: global-styles.php
 *
 * Handles dynamic features related to global styles.
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
 */

namespace WPBTS\Global_Styles;

add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\add_vars_to_preset_classes' );

/**
 * Add CSS variables to preset classes.
 *
 * For each color preset class (e.g. .has-{name}-color), add the equivalent
 * value as a locally-scoped CSS variable. This allows us to easily style
 * elements of a block based on the current color and background color settings.
 *
 * @todo Handle other presets (font sizes, etc.).
 */
function add_vars_to_preset_classes() {
	$stylesheet = '';
	$settings   = \wp_get_global_settings( [ 'color' ] );

	// Color presets.
	foreach ( $settings['palette'] as $colors ) {
		foreach ( $colors as $color ) {
			$stylesheet .= ".has-{$color['slug']}-color { --wpbts-color: var(--wp--preset--color--{$color['slug']}) !important; }\n.has-{$color['slug']}-background-color { --wpbts-background-color: var(--wp--preset--color--{$color['slug']}) !important; } .has-{$color['slug']}-border-color { --wpbts-border-color: var(--wp--preset--color--{$color['slug']}) !important; }\n\n";
		}
	}

	// Gradient presets.
	foreach ( $settings['gradients'] as $gradients ) {
		foreach ( $gradients as $gradient ) {
			$stylesheet .= ".has-{$gradient['slug']}-gradient-background { --wpbts-background: var(--wp--preset--gradient--{$gradient['slug']}) !important; }\n\n";
		}
	}

	\wp_register_style( 'wpbts-preset-classes', false );
	\wp_add_inline_style( 'wpbts-preset-classes', $stylesheet );
	\wp_enqueue_style( 'wpbts-preset-classes' );
}
