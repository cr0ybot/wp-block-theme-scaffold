/**
 * Custom webpack config.
 */

const scriptsConfig = require( '@wordpress/scripts/config/webpack.config' );
const { basename, dirname, parse } = require( 'path' );
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const CSSAssetFilesWebpackPlugin = require( './scripts/css-asset-files' );
const { getThemeEntries } = require( './scripts/entries' );
const { fromProjectRoot, getWordPressSrcDirectory } = require( './scripts/utils' );

const defaultConfigs = Array.isArray( scriptsConfig ) ? scriptsConfig : [ scriptsConfig ];

const configs = defaultConfigs.map( ( defaultConfig ) => {

	// Customize Sass loader options by finding the Sass loader rule (test: /\.(sc|sa)ss$/).
	const sassLoaderRule = defaultConfig.module.rules.find((rule) => {
		return rule.test.toString() === String(/\.(sc|sa)ss$/);
	});

	// Sass loader use object is last in the use array.
	const sassLoaderUse = sassLoaderRule.use[sassLoaderRule.use.length - 1];

	const includePaths = [
		`${fromProjectRoot( getWordPressSrcDirectory() )}/styles`,
	];

	sassLoaderUse.options = {
		...sassLoaderUse?.options,
		sassOptions: {
			// Add src/styles to includePaths (not loadPaths) for Sass loader.
			includePaths,
			// Ensure comments are preserved.
			outputStyle: 'expanded',
		},
	};

	if ( ! defaultConfig?.output?.module ) {
		return {
			...defaultConfig,
			entry: getThemeEntries( defaultConfig.entry ),
			optimization: {
				...( defaultConfig?.optimization ?? {} ),
				splitChunks: {
					...( defaultConfig?.optimization?.splitChunks ?? {} ),
					cacheGroups: {
						...( defaultConfig?.optimization?.splitChunks?.cacheGroups ??
							{} ),
						// Output individual CSS files with original filename.
						style: {
							type: 'css/mini-extract',
							test: /\.(sc|sa|c)ss$/,
							chunks: 'all',
							enforce: true,
							name( module, chunks, cacheGroupKey ) {
								const chunkName = dirname( chunks[ 0 ].name );
								const fileName = parse( module._identifier ).name;
								return `${ dirname( chunkName ) }/${ basename(
									chunkName
								) }/${ fileName }`;
							},
						},
						default: false,
					},
				},
			},
			module: {
				...defaultConfig.module,
				rules: [
					...( defaultConfig?.module?.rules ?? [] ),
					{
						test: /\.(sc|sa|c)ss$/,
						use: 'glob-import-loader',
					},
					{
						test: /\.(j|t)sx?$/,
						use: 'glob-import-loader',
					}
				],
			},
			plugins: [
				new RemoveEmptyScriptsPlugin({
					ignore: [
						/.*asset\.php$/,
					]
				}),
				new CSSAssetFilesWebpackPlugin(),
				...( defaultConfig?.plugins ?? [] ),
			]
		};
	}

	return defaultConfig;
} );

module.exports = configs;
