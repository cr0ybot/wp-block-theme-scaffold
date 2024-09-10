/**
 * Editor: block variations.
 *
 * Registers block variations that show up as separate blocks in the inserter.
 */

import { registerBlockVariation } from '@wordpress/blocks';

/**
 * Query loop that alternates flex direction of contained blocks.
 */
const CORE_QUERY_ALTERNATING = 'wpbts/query-alternating-flex-direction';
registerBlockVariation( 'core/query', {
	name: CORE_QUERY_ALTERNATING,
	title: 'Query: Alternating Alignments',
	description:
		'Query that alternates left/right alignment of contained blocks. Works best with a Row block in the Post Template.',
	isDefault: false,
	attributes: {
		namespace: CORE_QUERY_ALTERNATING,
		className: 'is-style-query-alternating-flex-direction',
	},
	isActive: [ 'className' ],
	scope: [ 'inserter' ],
	innerBlocks: [
		[
			'core/post-template',
			{},
			[
				[
					'core/group',
					{ layout: { type: 'flex', flexWrap: 'nowrap' } },
					[
						['core/post-featured-image'],
						[
							'core/group',
							{ layout: { type: 'flex', orientation: 'vertical' } },
							[
								['core/post-date'],
								['core/post-title'],
								['core/post-excerpt'],
							],
						],
					],
				],
			],
		],
	],
} );
