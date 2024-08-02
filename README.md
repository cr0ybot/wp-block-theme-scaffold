# Block Theme Scaffold for WordPress

A scaffold for block themes using the [GulpWP](https://github.com/cr0ybot/gulp-wp) workflow.

## Features

1. [Sass Style Framework](#sass-style-framework) - A structured Sass framework for building block themes.
2. [Block Style Overrides](#block-style-overrides) - Automatically enqueued block style overrides for core and third-party blocks.
3. [Custom Block Workflow](#custom-block-workflow) - A custom block development workflow using `@wordpress/scripts`.
4. [Theme.json and front end Global Styles switcher](#themejson-and-global-styles) - A `theme.json` file for defining global styles and settings, and a Global Style Switcher block for switching between alternate global styles (dark mode).
5. [Block Patterns, Parts, and Templates](#block-patterns-parts-and-templates) - Scaffolded `parts`, `patterns`, and `templates` folders.

### Sass Style Framework

Yes, this scaffold uses Sass instead of cobbling together PostCSS modules that may not play well together. I'm interested in developer experience and maintainability, and Sass is a great tool for that.

#### CSS Layers

The theme stylesheet defines 6 [@layer](https://developer.mozilla.org/en-US/docs/Web/CSS/@layer)s:

- *Reset* - Sanitize.css for a consistent base.
- *Base* - Base typography and global styles that don't fit in `theme.json`.
- *Layout* - Site layout styles that don't fit as part of a block.
- *Components* - Reusable components and patterns.
- *Blocks* - Block-specific styles.
- *Utilities* - Utility classes for quick styling.

Each layer has an equivalent folder in `src/styles/theme/` *except for blocks*, which are handled in either custom block styles or [block style overrides](#block-style-overrides). At the moment, you must manually include the layer tool when outputting block styles if you want them in the `blocks` layer.

#### Block Style Overrides

Place styles for core and third-party blocks in `src/styles/blocks/` within a "namespace" folder to have those styles automatically enqueued only when the block is present on the page. For example: `src/styles/blocks/core/spacer.scss` will be transpiled to `dist/css/blocks/core/spacer.css` which only be enqueued when the core spacer block is present on the page.

This is great for adding custom block styles to core blocks, which is a major part of modern theming.

You could also use these for your own custom blocks, but it's recommended to use the core `block.json` `styles` and `editorStyles` properties for custom block styles.

### Custom Block Workflow

`GulpWP` includes support for compiling custom blocks using the `@wordpress/scripts` package. Custom blocks should be created as subfolders in `src/blocks/` which are transpiled to `dist/blocks/` and automatically enqueued in `inc/blocks.php`.

### Theme.json and Global Styles

A block theme wouldn't be complete without a [theme.json](https://developer.wordpress.org/block-editor/reference-guides/block-themes/theme-json/) file. This file is used to define the theme's global styles and settings. The scaffold applies some sensible defaults to get you started, focused mainly around typography and color settings.

#### Style Presets and Global Style Switcher

The scaffold includes a `styles` folder (not to be confused with `src/styles/`) for alternate global styles. By default, a `dark.json` file is included for handling dark mode styles, and a Global Style Switcher block is included in the scaffold to handle which is displayed by default and switching between the two on the front end.

### Block Patterns, Parts, and Templates

The scaffold includes a `parts` folder for reusable block parts, a `patterns` folder for block patterns, and a `templates` folder for block templates. These are automatically recognized by WordPress and can be used in the block editor. Included is a set of patterns, parts, and templates with an opinionated file naming convention to help keep things organized.

Parts and templates can be overridden in the site editor, which is great for the longevity of a site, but not as great for initial site development. I recommend using the [Create Block Theme plugin](https://wordpress.org/plugins/create-block-theme/) to copy changes back to the theme files during development. WP Engine's [Pattern Manager plugin](https://wordpress.org/plugins/pattern-manager/) is also a great tool for doing the same with block patterns.
