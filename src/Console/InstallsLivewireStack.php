<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Livewire\Volt\Console\InstallCommand;

trait InstallsLivewireStack
{
    /**
     * Install the Livewire Breeze stack.
     *
     * @param  bool  $functional
     * @return int|null
     */
    protected function installLivewireStack($functional)
    {
        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@tailwindcss/forms' => '^0.5.2',
                'autoprefixer' => '^10.4.2',
                'postcss' => '^8.4.6',
                'tailwindcss' => '^3.1.0',
            ] + $packages;
        });

        // Install Livewire...
        if (! $this->requireComposerPackages(['livewire/livewire:^3.0', 'livewire/volt:^1.0'])) {
            return 1;
        }

        if ($this->call(InstallCommand::class) !== 0) {
            return 1;
        }

        // Layouts...
        (new Filesystem)->ensureDirectoryExists(resource_path('views/components/layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/resources/views/components/layouts', resource_path('views/layouts'));

        // Views Components...
        (new Filesystem)->ensureDirectoryExists(resource_path('views/components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/resources/views/components', resource_path('views/components'));

        // Views Layouts...
        (new Filesystem)->ensureDirectoryExists(resource_path('views/layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/resources/views/layouts', resource_path('views/layouts'));

        // Livewire Components
        (new Filesystem)->ensureDirectoryExists(resource_path('views/livewire'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/livewire/resources/views/livewire', resource_path('views/livewire'));

        // Livewire Config
        copy(__DIR__.'/../../stubs/livewire/config/livewire.php', base_path('config/livewire.php'));

        // Routes...
        copy(__DIR__.'/../../stubs/livewire/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/livewire/routes/auth.php', base_path('routes/auth.php'));

        // "Dashboard" Route...
        $this->replaceInFile('/home', '/dashboard', resource_path('views/welcome.blade.php'));
        $this->replaceInFile('Home', 'Dashboard', resource_path('views/welcome.blade.php'));
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

        $this->components->info('Livewire scaffolding installed successfully.');
    }
}
