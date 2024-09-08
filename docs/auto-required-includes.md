# Auto-Required Includes

PHP files in the root of the `includes` folder are automatically required in `functions.php`. This means that you cannot rely on a specific import order when using functions from other includes--but you don't need to worry about it. WordPress development is event-driven via action/filter hooks, so as long as your code references those external functions within hook callbacks, you're good to go, since all of the files will be included before the first hook is fired.

When adding functionality, consider adding a new include file with a descriptive name and namespace the file accordingly. For example, if you're adding a custom post type for a portfolio, you might create a file named `portfolio.php` and namespace your file as `WPBTS\Portfolio`. All other portfolio-related functions would be added to this same file.
