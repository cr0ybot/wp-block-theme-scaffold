/**
 * Script: entries.
 *
 * Handles globbing additional entries for Webpack from the src folder.
 *
 * /src/styles -> /css/*.css
 * /src/scripts -> /js/*.js
 */

const { basename, dirname, extname } = require( 'path' );
const { sync: glob } = require( 'fast-glob' );

const { fromProjectRoot, getWordPressSrcDirectory } = require( './utils' );

const getThemeEntries = ( defaultEntries = () => ({}) ) => {
	// Get all files in the root of the /src/styles directory.
	const styles = glob(
		'styles/*.*',
		{
			absolute: true,
			cwd: fromProjectRoot( getWordPressSrcDirectory() ),
		}
	);

	// Get all files in subfolders of /src/styles/blocks/.
	const blocks = glob(
		'styles/blocks/*/*.*',
		{
			absolute: true,
			cwd: fromProjectRoot( getWordPressSrcDirectory() ),
		}
	);

	// Get all files in the root of /src/styles/block-styles/.
	const blockStyles = glob(
		'styles/block-styles/*.*',
		{
			absolute: true,
			cwd: fromProjectRoot( getWordPressSrcDirectory() ),
		}
	);

	// Get all files in the root of the /src/scripts directory.
	const scripts = glob(
		'scripts/*.*',
		{
			absolute: true,
			cwd: fromProjectRoot( getWordPressSrcDirectory() ),
		}
	);

	return {
		// Styles are output to /css/*.css.
		...styles.reduce(
			( entries, file ) => ( {
				...entries,
				[`css/${ basename( file, extname( file ) ) }`]: file,
			} ),
			{}
		),
		// Block styles are output to /css/blocks/{blockname}/*.css.
		...blocks.reduce(
			( entries, file ) => ( {
				...entries,
				[`css/blocks/${ basename( dirname( file ) ) }/${ basename( file, extname( file ) ) }`]: file,
			} ),
			{}
		),
		// Block styles are output to /css/block-styles/*.css.
		...blockStyles.reduce(
			( entries, file ) => ( {
				...entries,
				[`css/block-styles/${ basename( file, extname( file ) ) }`]: file,
			} ),
			{}
		),
		// Scripts are output to /js/*.js.
		...scripts.reduce(
			( entries, file ) => ( {
				...entries,
				[`js/${ basename( file, extname( file ) ) }`]: file,
			} ),
			{}
		),
		...defaultEntries(),
	};
}

module.exports = {
	getThemeEntries,
};
