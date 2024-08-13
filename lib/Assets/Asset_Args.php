<?php
/**
 * Asset Args.
 *
 * @package WPBTS
 */

namespace WPBTS\Assets;

use WPBTS\Assets\Asset_Type;

/**
 * Asset Args class.
 * Handles registration arguments depending on asset type. For scripts, it
 * includes strategy and in_footer. For styles, it includes media.
 */
class Asset_Args {
	/**
	 * Script: strategy.
	 *
	 * @var string
	 */
	private ?string $strategy;

	/**
	 * Script: in_footer.
	 *
	 * @var bool
	 */
	private ?bool $in_footer;

	/**
	 * Style: media.
	 *
	 * @var string
	 */
	private ?string $media;

	/**
	 * Constructor.
	 *
	 * @param Asset_Type $type Asset type.
	 * @param array      $args Asset registration arguments.
	 */
	public function __construct( private Asset_Type $type, array $args = array() ) {
		if ( Asset_Type::script === $type ) {
			$this->strategy  = $args['strategy'] ?? null;
			$this->in_footer = $args['in_footer'] ?? false;
		} elseif ( Asset_Type::style === $type ) {
			$this->media = $args['media'] ?? 'all';
		}
	}

	/**
	 * Get args.
	 *
	 * @return array|string
	 */
	public function get_args() {
		return match ( $this->type ) {
			Asset_Type::script => array(
				'strategy'  => $this->strategy,
				'in_footer' => $this->in_footer,
			),
			Asset_Type::style => $this->media,
		};
	}
}
