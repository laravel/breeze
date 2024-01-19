<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

trait InstallsLivewireComponentsStack
{
    /**
     * Install the Livewire Breeze stack with components.
     *
     * @return int|null
     */
    protected function installLivewireComponentsStack()
    {
        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@tailwindcss/forms' => '^0.5.2',
                'autoprefixer' => '^10.4.2',
                'postcss' => '^8.4.31',
                'tailwindcss' => '^3.1.0',
            ] + $packages;
        });

        // Install Livewire...
        if (! $this->requireComposerPackages(['livewire/livewire:^3.0'])) {
            return 1;
        }

        // Auth Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire-components/app/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Livewire Actions, Forms and Components...
        (new Filesystem)->ensureDirectoryExists(app_path('Livewire'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire-components/app/Livewire', app_path('Livewire'));

        // Components...
        (new Filesystem)->ensureDirectoryExists(app_path('View/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/View/Components', app_path('View/Components'));

        // Views...
        (new Filesystem)->ensureDirectoryExists(resource_path('views'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire-components/resources/views', resource_path('views'));

        // Dark mode...
        if (! $this->option('dark')) {
            $this->removeDarkClasses((new Finder)
                ->in(resource_path('views'))
                ->name('*.blade.php')
                ->notName('welcome.blade.php')
            );
        }

        // Tests...
        if (! $this->installTests()) {
            return 1;
        }

        // Routes...
        copy(__DIR__.'/../../stubs/livewire-components/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/livewire-components/routes/auth.php', base_path('routes/auth.php'));

        // "Dashboard" Route...
        $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/default/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));

        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->components->info('Livewire with Components scaffolding installed successfully.');
    }
}
