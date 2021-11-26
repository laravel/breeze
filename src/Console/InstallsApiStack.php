<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;

trait InstallsApiStack
{
    /**
     * Install the API Breeze stack.
     *
     * @return void
     */
    protected function installApiStack()
    {
        $files = new Filesystem;

        // Controllers...
        $files->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        $files->copyDirectory(__DIR__.'/../../stubs/api/App/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Middleware...
        $files->copyDirectory(__DIR__.'/../../stubs/api/App/Http/Middleware', app_path('Http/Middleware'));

        $this->replaceInFile('// \Laravel\Sanctum\Http', '\Laravel\Sanctum\Http', app_path('Http/Kernel.php'));
        $this->replaceInFile('\Illuminate\Auth\Middleware\EnsureEmailIsVerified::class', '\App\Http\Middleware\EnsureEmailIsVerified::class', app_path('Http/Kernel.php'));

        // Requests...
        $files->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        $files->copyDirectory(__DIR__.'/../../stubs/api/App/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        // Providers...
        $files->copyDirectory(__DIR__.'/../../stubs/api/App/Providers', app_path('Providers'));

        // Configuration...
        $files->copyDirectory(__DIR__.'/../../stubs/api/config', config_path());

        // Tests...
        $this->installTests();

        $files->delete(base_path('tests/Feature/Auth/PasswordConfirmationTest.php'));

        // Routes...
        copy(__DIR__.'/../../stubs/api/routes/api.php', base_path('routes/api.php'));
        copy(__DIR__.'/../../stubs/api/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/api/routes/auth.php', base_path('routes/auth.php'));

        $this->removeScaffoldingUnnecessaryForApis();

        $this->info('Breeze scaffolding installed successfully.');
    }

    /**
     * Remove any application scaffolding that isn't needed for APIs.
     *
     * @return void
     */
    protected function removeScaffoldingUnnecessaryForApis()
    {
        $files = new Filesystem;

        // Remove frontend related files...
        $files->delete(base_path('package.json'));
        $files->delete(base_path('webpack.mix.js'));

        // Remove Laravel "welcome" view...
        $files->delete(resource_path('views/welcome.blade.php'));
        $files->put(resource_path('views/.gitkeep'), PHP_EOL);

        // Remove CSS and JavaScript directories...
        $files->deleteDirectory(resource_path('css'));
        $files->deleteDirectory(resource_path('js'));
    }
}
