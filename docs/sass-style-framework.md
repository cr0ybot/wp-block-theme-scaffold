# Sass Style Framework

Yes, this scaffold uses Sass instead of cobbling together PostCSS modules that may not play well together. I'm interested in developer experience and maintainability, and Sass is a great tool for that. And if you're going to use JSX for React/JS, you might as well use Sass for CSS.

## CSS Layers

The theme stylesheet defines 6 [@layer](https://developer.mozilla.org/en-US/docs/Web/CSS/@layer)s:

- *Reset* - Sanitize.css for a consistent base.
- *Base* - Base typography and global styles that don't fit in `theme.json`.
- *Layout* - Site layout styles that don't fit as part of a block.
- *Components* - Reusable components and patterns.
- *Blocks* - Block-specific styles.
- *Utilities* - Utility classes for quick styling.

Each layer has an equivalent folder in `src/styles/theme/` *except for blocks*, which are handled in either custom block styles or [block style overrides](/docs/block-style-overrides). At the moment, you must manually include the layer tool when outputting block styles if you want them in the `blocks` layer:

```scss
@use "../../tools/layers";

@include layers.add-to(blocks) {
	// Your block styles here
}
```
