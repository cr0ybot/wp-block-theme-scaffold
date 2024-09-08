# Block Style Overrides

Place styles for core and third-party blocks in `src/styles/blocks/` within a "namespace" folder to have those styles automatically enqueued only when the block is present on the page. For example: `src/styles/blocks/core/spacer.scss` will be transpiled to `dist/css/blocks/core/spacer.css` which will only be enqueued when the core spacer block is present on the page.

This is great for adding custom block styles to core blocks, which is a major part of modern theming.

You could also use these for your own custom blocks, but it's recommended to use the core `block.json`'s `style` and `editorStyle` properties for custom block styles.
