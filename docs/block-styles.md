# Block Styles

[Block styles](https://developer.wordpress.org/themes/features/block-style-variations/) (not to be confused with [block stylesheet overrides](/docs/block-stylesheet-overrides)) are a way to create alternately styled variations (not to be confused with [block variations](https://developer.wordpress.org/themes/features/block-variations/)) of a block.

This framework includes a system for easily defining block styles that are automatically registered and enqueued when the block is present on the page (see `includes/block-styles.php`). Add a Sass file in `src/styles/block-styles/`, ideally with the same name as your style slug. Any stylesheets in this folder are automatically registered with the handle `wpbts/block-styles/{filename}`. If you want the styles to be automatically enqueued, add a file header comment with these parameters:

- `Title` - Required. The title of the block style.
- `Slug` - Optional. The slug of the block style. Defaults to the filename.
- `Block Types` - Required. A comma-separated list of block types that the style applies to.
- `Is Default` - Optional. Whether the style is the default style for the block. Defaults to `false`.
- `Description` - Optional. A description of the block style, mostly for your own reference.

Example:

```scss
/*!
 * Title: My Block Style
 * Slug: my-block-style
 * Block Types: core/paragraph,core/heading
 * Is Default: true
 * Description: A description of the block style.
 */
```

*Note that in Sass the comment must be prepended with `/*!` to prevent it from being removed during compilation.*

If you set `Is Default` to `true`, you will likely want to use the block's default classname or tagname in your stylesheet, since the default block style generally doesn't apply the style classname to the block.
