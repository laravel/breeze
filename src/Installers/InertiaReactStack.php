<?php

namespace Laravel\Breeze\Installers;

use Illuminate\Filesystem\Filesystem;
use Laravel\Breeze\Contracts\StackInstaller;

class InertiaReactStack extends AbstractInstaller implements StackInstaller
{
    /**
     * Install the API Breeze stack.
     *
     * @return void
     */
    public function install(): void
    {
        // Install Inertia...
        $this->requireComposerPackages('inertiajs/inertia-laravel:^0.6.3', 'laravel/sanctum:^2.8', 'tightenco/ziggy:^1.0');

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@headlessui/react' => '^1.4.2',
                '@inertiajs/inertia' => '^0.11.0',
                '@inertiajs/inertia-react' => '^0.8.1',
                '@inertiajs/progress' => '^0.2.6',
                '@tailwindcss/forms' => '^0.5.3',
                '@vitejs/plugin-react' => '^2.0.0',
                'autoprefixer' => '^10.4.12',
                'postcss' => '^8.4.18',
                'tailwindcss' => '^3.2.1',
                'react' => '^18.2.0',
                'react-dom' => '^18.2.0',
            ] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers', app_path('Http/Controllers'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');
        $this->installMiddlewareAfter('\App\Http\Middleware\HandleInertiaRequests::class', '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class');

        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-react/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Pages', resource_path('js/Pages'));

        if (! $this->command->option('dark')) {
            $this->removeDarkClasses((new Finder)
                ->in(resource_path('js'))
                ->name('*.jsx')
                ->notName('Welcome.jsx')
            );
        }

        // Tests...
        $this->installTests();
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/tests/Feature', base_path('tests/Feature'));

        // Routes...
        copy(__DIR__.'/../../stubs/inertia-common/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/inertia-common/routes/auth.php', base_path('routes/auth.php'));

        // "Dashboard" Route...
        $this->replaceInFile('/home', '/dashboard', resource_path('js/Pages/Welcome.jsx'));
        $this->replaceInFile('Home', 'Dashboard', resource_path('js/Pages/Welcome.jsx'));
        $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/jsconfig.json', base_path('jsconfig.json'));
        copy(__DIR__.'/../../stubs/inertia-react/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__.'/../../stubs/inertia-react/resources/js/app.jsx', resource_path('js/app.jsx'));

        if (file_exists(resource_path('js/app.js'))) {
            unlink(resource_path('js/app.js'));
        }

        $this->replaceInFile('.vue', '.jsx', base_path('tailwind.config.js'));

        if ($this->command->option('ssr')) {
            $this->installInertiaReactSsrStack();
        }

        $this->runCommands(['npm install', 'npm run build']);

        $this->command->line('');
        $this->command->components->info('Breeze scaffolding installed successfully.');
    }

    /**
     * Install the Inertia React SSR stack into the application.
     *
     * @return void
     */
    protected function installInertiaReactSsrStack()
    {
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/server' => '^0.1.0',
            ] + $packages;
        });

        copy(__DIR__.'/../../stubs/inertia-react/resources/js/ssr.jsx', resource_path('js/ssr.jsx'));
        $this->replaceInFile("input: 'resources/js/app.jsx',", "input: 'resources/js/app.jsx',".PHP_EOL."            ssr: 'resources/js/ssr.jsx',", base_path('vite.config.js'));
        $this->replaceInFile('});', '    ssr: {'.PHP_EOL."        noExternal: ['@inertiajs/server'],".PHP_EOL.'    },'.PHP_EOL.'});', base_path('vite.config.js'));

        (new Process([$this->phpBinary(), 'artisan', 'vendor:publish', '--provider=Inertia\ServiceProvider', '--force'], base_path()))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->command->output->write($output);
            });

        $this->replaceInFile("'enabled' => false", "'enabled' => true", config_path('inertia.php'));
        $this->replaceInFile('vite build', 'vite build && vite build --ssr', base_path('package.json'));
        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
    }
}
