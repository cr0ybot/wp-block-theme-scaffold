# Sass Style Framework

Yes, this scaffold uses Sass instead of cobbling together PostCSS modules that may not play well together. I'm interested in developer experience and maintainability, and Sass is a great tool for that. And if you're going to use JSX for React/JS, you might as well use Sass for CSS. My hope is that the features below might convince you that Sass is still relevant and good, even.

Sass Framework Features:

1. [Theme Style Layers](#theme-style-layers) - A layered approach to organizing styles.
2. [Contexts](#contexts) - A way to output styles in different contexts.
3. [Breakpoints](#breakpoints) - A way to define and use breakpoints in both media & container queries.

## Theme Style Layers

Not to be confused with [CSS Cascade Layers](https://css-tricks.com/css-cascade-layers/), these layers are just a system of organizing styles so that you don't get in your own way. Note that styles within an `@layer` are always [less specific than unlayered styles](https://github.com/w3c/csswg-drafts/issues/6284#issuecomment-937262197), so they're practically useless for us in this context where WordPress core and plugin stylesheets are out of our control.

The `src/styles/theme` folder includes 6 layer folders:

- *Reset* - Includes sanitize.css for a consistent base.
- *Base* - Base typography and global styles that don't fit in `theme.json`.
- *Layout* - Site layout styles that don't fit as part of a block.
- *Components* - Reusable components and patterns.
- *Utilities* - Utility classes for quick styling.

The contents of these folders are glob-imported from the entry file (in this case, `frontend.scss` and `editor.scss`) in the above order, going from the most general to the most specific. This allows you to override styles in the more general layers with styles in the more specific layers.

Because the contents of these folders are glob-imported, you cannot rely on the order of the files within each folder. However, you should not need a specific order within each layer, as you should rely instead on selector specificity.

### Block Stylesheets

Styles for blocks are unique in that they are generally not part of the main theme stylesheet, which means there is no "blocks" folder in `src/styles/theme`. Instead, block styles are handled in either [custom blocks](/docs/custom-block-workflow.md) or [block style overrides](/docs/block-style-overrides).

## Contexts

Somewhat unique to WordPress theme development, we have to contend with our styles being output in different contexts such as both on the frontend as well as in the block editor. Having a separate "editor-overrides" stylesheet is a common way to handle this, but doing it this way decouples the styles from their original context. This can lead to confusion and maintenance issues, not to mention a bloated file. Instead, this framework keeps the styles in their original context and outputs them in different entry file contexts as needed.

The `context` tool at `src/styles/tools/context.scss` is a Sass module that allows you to define different contexts for outputting (or not outputting) styles. Each entry file should define a context that matches the file name, as the default `frontend.scss`, `editor.scss`, and `admin.scss` entry files do. Note that if you do not define a context in the entry file, the context defaults to "frontend". If (and when) styles are output simultaneously to multiple entry file contexts, you can control which styles are used with a few available mixins:

```scss
@use "tools/context";

.foo {
	color: green;
	// The `is` mixin outputs styles only in the specified context.
	@include context.is(editor) {
		color: red;
	}

	background: yellow;
	// The `not` mixin outputs styles in all contexts except the specified one.
	@include context.not(frontend) {
		background: purple;
	}

	border-color: orange;
	// The `any` mixin outputs styles only in any of the specified contexts.
	@include context.any(frontend, editor) {
		border-color: blue;
	}
}
```

**Output frontend.css:**

```css
.foo {
	color: green;
	background: yellow;
	border-color: blue;
}
```

**Output editor.css:**

```css
.foo {
	color: red;
	background: purple;
	border-color: blue;
}
```

**Note that the context tool does not currently work with block styles or block stylesheet overrides, as these do not yet have separate editor-specific stylesheets to define a context for.**

## Breakpoints

The `breakpoint` tool at `src/styles/tools/breakpoint.scss` is a Sass module that allows you to define and use breakpoints in both media queries and container queries. Several default `$breakpoints` are defined as a map of breakpoint names and values, which can be directly edited for your project or overridden when `@use`ing the module.

Breakpoints are defined as a single pixel value ([pixels are currently the most consistent across browsers](https://keithjgrant.com/posts/2023/05/px-vs-em-in-media-queries/) as of May 2023), and the output uses [range syntax](https://css-tricks.com/the-new-css-media-query-range-syntax/) to avoid 1px style overlaps.

Several breakpoint functions are available for use within `@media` and `@container` queries. Note that they are "mobile-first" by default, so the `at` function will output breakpoint equivalent to a `min-width` query, and the `under` function will output something similar to a `max-width` query except the breakpoint value is exclusive.

Each function has at least two parameters: the breakpoint name or value (or two values for `between`), and an optional dimension parameter (`width` or `height`). The dimension parameter is optional and defaults to `width`.

```scss
@use "tools/breakpoint";

.foo {
	color: red;

	// At and above the sm breakpoint.
	@media (breakpoint.at(sm)) {
		color: blue;
	}

	// Below the md breakpoint in the height dimension.
	@container (breakpoint.under(md, height)) {
		color: green;
	}

	// At and above the sm breakpoint and below the lg breakpoint in the height dimension.
	@media (breakpoint.between(sm, lg, height)) {
		color: yellow;
	}

	// You may also pass custom values instead of the predefined breakpoint names.
	@container (breakpoint.at(800px)) {
		color: purple;
	}
}
```

**Output:**

```css
.foo {
	color: red;

	@media (width >= 600px) {
		color: blue;
	}

	@container (height < 768px) {
		color: green;
	}

	@media (600px <= height < 1024px) {
		color: yellow;
	}

	@container (width >= 800px) {
		color: purple;
	}
}
```
