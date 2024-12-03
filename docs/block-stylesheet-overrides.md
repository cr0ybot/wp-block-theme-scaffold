# Block Stylesheet Overrides

[Block stylesheet](https://developer.wordpress.org/themes/features/block-stylesheets/) overrides (not to be confused with [block styles](/docs/block-styles.md)) are stylesheets that are automatically enqueued only when a block is present on the page. They do not replace the block's default styles, but rather add to them. This is a great way to add custom styles to core and third-party blocks that can't be accomplished through `theme.json`.

Place styles for core and third-party blocks in `src/styles/blocks/` within a "namespace" folder to have those styles automatically enqueued. For example: `src/styles/blocks/core/spacer.scss` will be transpiled to `dist/css/blocks/core/spacer.css` which will only be enqueued when the core spacer block is present on the page.

You could also use these for your own custom blocks, but it's recommended to use the core `block.json`'s `style` and `editorStyle` properties for custom block stylesheets.
