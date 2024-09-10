# WP Env

This scaffold includes the official Docker-powered local development environment for WordPress: [@wordpress/env](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/). This is an optional feature that you can use to develop your theme locally, without needing to install PHP, MySQL, or any other server software on your computer. You do, however, need to have [Docker](https://docs.docker.com/desktop/) installed.

To set up the environment:

```bash
npm run env:setup
```

This will install WordPress in the container and run it at `http://localhost:8888`. You can access the WordPress admin at `http://localhost:8888/wp-admin` with the username `admin` and the password `password`.

To stop the environment:

```bash
npm run env:stop
```

To start the environment again:

```bash
npm run env:start
```

And to *destroy* (removes all data!) the environment:

```bash
npm run env:destroy
```

## Debugging

By default, XDebug is enabled in the local environment when you run `npm run env:start`. It is recommended to read the [documentation about XDebug IDE support](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/#xdebug-ide-support).

The WordPress debug log is also available in the `logs` directory in the root of the project.
