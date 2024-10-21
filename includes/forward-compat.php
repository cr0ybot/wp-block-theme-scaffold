<?php
/**
 * Include: forward-compat.php
 *
 * Handles forward compatibility with WordPress 6.7 and beyond.
 */

namespace WPBTS\Forward_Compat;

add_filter( 'block_type_metadata', __NAMESPACE__ . '\\filter_block_type_metadata', 10, 2 );

/**
 * Filter block type metadata to add support for upcoming features.
 *
 * @param array  $metadata The block type metadata.
 * @param string $block_name The block name.
 * @return array The filtered block type metadata.
 */
function filter_block_type_metadata( array $metadata ): array {
	// Add support for shadow controls on group blocks.
	if ( 'core/group' === $metadata['name'] ) {
		if ( ! isset( $metadata['supports']['shadow'] ) ) {
			$metadata['supports']['shadow'] = true;
		}
	}

	// Add support for renaming navigation blocks.
	if ( 'core/navigation' === $metadata['name'] ) {
		$metadata['supports']['renaming'] = true;
	}

	// Add support for background color on image blocks.
	if ( 'core/image' === $metadata['name'] ) {
		$metadata['supports']['color'] = [
			'background' => true,
		];
	}

	return $metadata;
}
