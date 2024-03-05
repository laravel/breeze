# Upgrade Guide

## Upgrading from Breeze 1.x to Breeze 2.x

#### Dependency Changes

Unlike other starter kits such as Jetstream, the Laravel Breeze dependency can be removed after you run the `breeze:install` Artisan command. Therefore, if you are in the process of upgrading to Laravel 11, we advise you to simply remove the `laravel/breeze` dependency from your application's `composer.json` file. Then, run the `composer update` command:

    composer update

