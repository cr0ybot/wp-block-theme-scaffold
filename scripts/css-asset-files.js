/**
 * Script: css-asset-files.
 */

const path = require( 'path' );
const webpack = require( 'webpack' );
const json2php = require( 'json2php' );
const { createHash } = webpack.util;

const { RawSource } = webpack.sources;

class CSSAssetFilesWebpackPlugin {
	constructor( options ) {
		this.options = Object.assign(
			{
				combineAssets: false,
				combinedOutputFile: null,
				injectPolyfill: false,
				outputFormat: 'php',
				outputFilename: null,
				useDefaults: true,
			},
			options
		);
	}

	/**
	 * @param {any} asset Asset Data
	 * @return {string} Stringified asset data suitable for output
	 */
	stringify( asset ) {
		if ( this.options.outputFormat === 'php' ) {
			return `<?php return ${ json2php(
				JSON.parse( JSON.stringify( asset ) )
			) };\n`;
		}

		return JSON.stringify( asset );
	}

	/** @type {webpack.WebpackPluginInstance['apply']} */
	apply( compiler ) {
		compiler.hooks.thisCompilation.tap(
			this.constructor.name,
			( compilation ) => {
				compilation.hooks.processAssets.tap(
					{
						name: this.constructor.name,
						stage: compiler.webpack.Compilation
							.PROCESS_ASSETS_STAGE_ANALYSE,
					},
					() => this.addAssets( compilation )
				);
			}
		);
	}

	/** @param {webpack.Compilation} compilation */
	addAssets( compilation ) {
		const {
			combineAssets,
			combinedOutputFile,
			outputFormat,
			outputFilename,
		} = this.options;

		const combinedAssetsData = {};

		// Accumulate all entrypoint chunks, some of them shared
		const entrypointChunks = new Set();
		for ( const entrypoint of compilation.entrypoints.values() ) {
			for ( const chunk of entrypoint.chunks ) {
				entrypointChunks.add( chunk );
			}
		}

		// Process each entrypoint chunk independently
		for ( const chunk of entrypointChunks ) {
			const chunkFiles = Array.from( chunk.files );

			const jsExtensionRegExp = this.useModules ? /\.m?js$/i : /\.js$/i;

			const chunkJSFile = chunkFiles.find( ( f ) =>
				jsExtensionRegExp.test( f )
			);

			if ( chunkJSFile ) {
				// There is a JS file in this chunk, so we can assume that we don't need to create an asset file.
				return;
			}

			const cssExtensionRegExp = /\.[sa|sc|c]ss$/i;

			const chunkCSSFile = chunkFiles.find( ( f ) =>
				cssExtensionRegExp.test( f )
			);
			if ( ! chunkCSSFile ) {
				// There's no CSS file in this chunk, no work for us.
				continue;
			}

			// Prepare to hash the sources. We can't just use
			// `chunk.contentHash` because that's not updated when
			// assets are minified. In practice the hash is updated by
			// `RealContentHashPlugin` after minification, but it only modifies
			// already-produced asset filenames and the updated hash is not
			// available to plugins.
			const { hashFunction, hashDigest, hashDigestLength } =
				compilation.outputOptions;

			const hashBuilder = createHash( hashFunction );

			const processContentsForHash = ( content ) => {
				hashBuilder.update( content );
			};

			// Go through the assets to process the sources.
			// This allows us to generate hashes, as well as look for magic comments.
			chunkFiles.sort().forEach( ( filename ) => {
				const asset = compilation.getAsset( filename );
				const content = asset.source.buffer();

				processContentsForHash( content );
			} );

			// Finalise hash.
			const contentHash = hashBuilder
				.digest( hashDigest )
				.slice( 0, hashDigestLength );

			const assetData = {
				// Keep dependencies array for consistency.
				dependencies: [],
				version: contentHash,
			};

			if ( combineAssets ) {
				combinedAssetsData[ chunkCSSFile ] = assetData;
				continue;
			}

			let assetFilename;
			if ( outputFilename ) {
				assetFilename = compilation.getPath( outputFilename, {
					chunk,
					filename: chunkCSSFile,
					contentHash,
				} );
			} else {
				const suffix =
					'.asset.' + ( outputFormat === 'php' ? 'php' : 'json' );
				assetFilename = compilation
					.getPath( '[file]', { filename: chunkCSSFile } )
					.replace( /\.[sa|sc|c]ss$/i, suffix );
			}

			// Add source and file into compilation for webpack to output.
			compilation.assets[ assetFilename ] = new RawSource(
				this.stringify( assetData )
			);
			chunk.files.add( assetFilename );
		}

		if ( combineAssets ) {
			const outputFolder = compilation.outputOptions.path;

			const assetsFilePath = path.resolve(
				outputFolder,
				combinedOutputFile ||
					'assets.' + ( outputFormat === 'php' ? 'php' : 'json' )
			);
			const assetsFilename = path.relative(
				outputFolder,
				assetsFilePath
			);

			// Add source into compilation for webpack to output.
			compilation.assets[ assetsFilename ] = new RawSource(
				this.stringify( combinedAssetsData )
			);
		}
	}
}

module.exports = CSSAssetFilesWebpackPlugin;
