module.exports ={
	extends: ['@wordpress/stylelint-config/scss'],
	plugins: ['stylelint-order'],
	rules: {
		// Properties in alphabetical order
		'order/properties-alphabetical-order': true,
		// Disable custom-property-pattern rule.
		'custom-property-pattern': null,
		// Disable scss/comment-no-empty rule.
		'scss/comment-no-empty': null,
		// Ignore !default in annotation-no-unkown rule.
		'annotation-no-unknown': [
			true,
			{
				ignoreAnnotations: ['default'],
			},
		],
		// Allow "redundant" longhand properties for things like more readable grid-template-areas.
		'declaration-block-no-redundant-longhand-properties': null,
		// Turn off class pattern rule.
		'selector-class-pattern': null,
		// Turn off nested selector rule.
		'selector-nested-pattern': null,
		// Exceptions to at-rule-empty-line-before.
		'at-rule-empty-line-before': [
			'always',
			{
				except: ['blockless-after-same-name-blockless', 'first-nested'],
				ignore: ['after-comment'],
				ignoreAtRules: ['if', 'else', 'warn', 'error'],
			},
		],
		// Fix known functions.
		'function-no-unknown': [
			true,
			{
				ignoreFunctions: ['if'],
			},
		],
		// Allow for breakpoint functions in media queries.
		// TODO: Validate custom functions instead of turning off.
		'media-feature-name-no-unknown': null,
	}
}
