<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

trait InstallsForgeDefaultStack
{
    /**
     * Install the Blade Breeze stack.
     *
     * @return int|null
     */
    protected function installForgeDefaultStack()
    {
        // Composer Packages...
        if ( ! $this->hasComposerPackage( 'laravel/fortify' ) )
        {
            $this->requireComposerPackages( [ 'laravel/fortify' ], false );
            $this->runCommands( [ 'php artisan fortify:install' ] );
        }

        // NPM Packages...
        $this->updateNodePackages( function ( $packages ) {
            return [
                       "@tailwindcss/vite" => "^4.1.16",
                       "alpinejs"          => "^3.15.2",
                       "tailwindcss"       => "^4.1.16",
                   ] + $packages;
        } );

        // Service Providers...
        ( new Filesystem )->ensureDirectoryExists( app_path( 'Providers' ) );
        ( new Filesystem )->copyDirectory( __DIR__ . '/../../stubs/forge-default/app/Providers', app_path( 'Providers' ) );

        // Controllers...
        ( new Filesystem )->ensureDirectoryExists( app_path( 'Http/Controllers' ) );
        ( new Filesystem )->copyDirectory( __DIR__ . '/../../stubs/forge-default/app/Http/Controllers', app_path( 'Http/Controllers' ) );

        // Views...
        ( new Filesystem )->ensureDirectoryExists( resource_path( 'views' ) );
        ( new Filesystem )->copyDirectory( __DIR__ . '/../../stubs/forge-default/resources/views', resource_path( 'views' ) );

        if ( ! $this->option( 'dark' ) )
        {
            $this->removeDarkClasses( ( new Finder )
                                          ->in( resource_path( 'views' ) )
                                          ->name( '*.blade.php' )
                                          ->notPath( 'livewire/welcome/navigation.blade.php' )
                                          ->notName( 'welcome.blade.php' )
            );
        }

        // Components...
        ( new Filesystem )->ensureDirectoryExists( app_path( 'View/Components' ) );
        ( new Filesystem )->copyDirectory( __DIR__ . '/../../stubs/forge-default/app/View/Components', app_path( 'View/Components' ) );

        // Tests...
        if ( ! $this->installTests() )
        {
            return 1;
        }

        // Routes...
        copy( __DIR__ . '/../../stubs/forge-default/routes/auth.php', base_path( 'routes/auth.php' ) );
        $routesToAppend = file_get_contents( __DIR__ . '/../../stubs/forge-default/routes/web.php.append' );
        $this->appendToFile( $routesToAppend, base_path( 'routes/web.php' ) );

        // "Dashboard" Route...
        $this->replaceInFile( '/home', '/dashboard', resource_path( 'views/welcome.blade.php' ) );
        $this->replaceInFile( 'Home', 'Dashboard', resource_path( 'views/welcome.blade.php' ) );

        // Tailwind / Vite...
        copy( __DIR__ . '/../../stubs/forge-default/tailwind.config.js', base_path( 'tailwind.config.js' ) );
        copy( __DIR__ . '/../../stubs/forge-default/vite.config.js', base_path( 'vite.config.js' ) );
        $appCssToAppend = file_get_contents( __DIR__ . '/../../stubs/forge-default/resources/css/app.css' );
        $this->appendToFile( $appCssToAppend, base_path( 'resources/css/app.css' ) );
        copy( __DIR__ . '/../../stubs/forge-default/resources/js/theme.js', resource_path( 'js/theme.js' ) );
        $appJsToAppend = file_get_contents( __DIR__ . '/../../stubs/forge-default/resources/js/app.js' );
        $this->appendToFile( $appJsToAppend, base_path( 'resources/js/app.js' ) );

        $this->installNodeModules();

        $this->line( '' );
        $this->components->info( 'Breeze scaffolding installed successfully.' );
    }
}
