<?php
/**
 * Assets/Asset_Type.php
 *
 * @author    Cory Hughart <cory@coryhughart.com>
 * @copyright 2024 Cory Hughart
 * @license   https://www.gnu.org/licenses/gpl-3.0.html GPL-3.0-or-later
 * @link      https://github.com/cr0ybot/wp-block-theme-scaffold
 * @package   WPBTS
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
