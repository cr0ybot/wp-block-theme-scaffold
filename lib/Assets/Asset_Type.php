<?php
/**
 * Asset Type enum.
 *
 * @package WPBTS
 */

namespace WPBTS\Assets;

/**
 * Asset Type enum.
 */
enum Asset_Type {
	case script;
	case style;

	/**
	 * Get file extension for asset type.
	 */
	public function get_extension(): string {
		return match ( $this ) {
			self::script => 'js',
			self::style  => 'css',
		};
	}
}
