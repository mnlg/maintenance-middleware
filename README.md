# Slim Maitenance Middleware

This repository contains an optional middleware for your Slim Framework application.

The middleware will return a default maitenance page with a 503 status code when the application is in `maintenance` mode.

## How to install

Update your `composer.json` manifest to require the `mnlg/maintenance-middleware` package (see below).
Run `composer install` or `composer update` to update your local vendor folder.

    {
        "require": {
            "mnlg/maintenance-middleware": "*"
        }
    }

## How to use

Add the maintenance middleware to your Slim applicaiton:

    <?php
    $app->add(new \Mnlg\Middleware\Maintenance());

You can also pass a callable function that will be called when maintenance mode is enabled:

    <?php
    $app->add(new \Mnlg\Middleware\Maintenance(function() {
        ...
    }));

To enable the maintenance mode just set the application mode to `maintenance` during the application instantiation:

    <?php
    $app = new \Mnlg\Slim(array(
        'mode'=>'maintenance'
    ));

Or using the $_ENV variable:

    <?php
    $_ENV['SLIM_MODE'] = 'maintenance';

## License

All code in this repository is released under the MIT public license.

<http://opensource.org/licenses/MIT>