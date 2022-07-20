<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

trait InstallsInertiaStacks
{
    /**
     * Install the Inertia Vue Breeze stack.
     *
     * @return void
     */
    protected function installInertiaVueStack()
    {
        // Install Inertia...
        $this->requireComposerPackages('inertiajs/inertia-laravel:^0.5.4', 'laravel/sanctum:^2.8', 'tightenco/ziggy:^1.0');

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/inertia' => '^0.11.0',
                '@inertiajs/inertia-vue3' => '^0.6.0',
                '@inertiajs/progress' => '^0.2.7',
                '@tailwindcss/forms' => '^0.5.2',
                '@vitejs/plugin-vue' => '^3.0.0',
                'autoprefixer' => '^10.4.2',
                'postcss' => '^8.4.6',
                'tailwindcss' => '^3.1.0',
                'vue' => '^3.2.31',
            ] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/App/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');

        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-common/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Pages', resource_path('js/Pages'));

        // Tests...
        $this->installTests();

        // Routes...
        copy(__DIR__.'/../../stubs/inertia-common/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/inertia-common/routes/auth.php', base_path('routes/auth.php'));

        // "Dashboard" Route...
        $this->replaceInFile('/home', '/dashboard', resource_path('js/Pages/Welcome.vue'));
        $this->replaceInFile('Home', 'Dashboard', resource_path('js/Pages/Welcome.vue'));
        $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/jsconfig.json', base_path('jsconfig.json'));
        copy(__DIR__.'/../../stubs/inertia-vue/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__.'/../../stubs/inertia-vue/resources/js/app.js', resource_path('js/app.js'));

        if ($this->option('ssr')) {
            $this->installInertiaVueSsrStack();
        }

        $this->components->info('Breeze scaffolding installed successfully.');
        $this->components->warn('Please execute the [npm install && npm run dev] commands to build your assets.');
    }

    /**
     * Install the Inertia Vue SSR stack into the application.
     *
     * @return void
     */
    protected function installInertiaVueSsrStack()
    {
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/server' => '^0.1.0',
                '@vue/server-renderer' => '^3.2.31',
            ] + $packages;
        });

        copy(__DIR__.'/../../stubs/inertia-vue/resources/js/ssr.js', resource_path('js/ssr.js'));
        $this->replaceInFile("input: 'resources/js/app.js',", "input: 'resources/js/app.js',".PHP_EOL."            ssr: 'resources/js/ssr.js',", base_path('vite.config.js'));
        $this->replaceInFile('});', '    ssr: {'.PHP_EOL."        noExternal: ['@inertiajs/server'],".PHP_EOL.'    },'.PHP_EOL.'});', base_path('vite.config.js'));

        (new Process([$this->phpBinary(), 'artisan', 'vendor:publish', '--provider=Inertia\ServiceProvider', '--force'], base_path()))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });

        $this->replaceInFile("'enabled' => false", "'enabled' => true", config_path('inertia.php'));
        $this->replaceInFile('vite build', 'vite build && vite build --ssr', base_path('package.json'));
        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
    }

    /**
     * Install the Inertia React Breeze stack.
     *
     * @return void
     */
    protected function installInertiaReactStack()
    {
        // Install Inertia...
        $this->requireComposerPackages('inertiajs/inertia-laravel:^0.5.4', 'laravel/sanctum:^2.8', 'tightenco/ziggy:^1.0');

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@headlessui/react' => '^1.4.2',
                '@inertiajs/inertia' => '^0.11.0',
                '@inertiajs/inertia-react' => '^0.8.0',
                '@inertiajs/progress' => '^0.2.6',
                '@tailwindcss/forms' => '^0.5.2',
                '@vitejs/plugin-react' => '^2.0.0',
                'autoprefixer' => '^10.4.2',
                'postcss' => '^8.4.6',
                'tailwindcss' => '^3.1.0',
                'react' => '^17.0.2',
                'react-dom' => '^17.0.2',
            ] + $packages;
        });

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers/Auth', app_path('Http/Controllers/Auth'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/App/Http/Requests/Auth', app_path('Http/Requests/Auth'));

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');

        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-common/resources/views/app.blade.php', resource_path('views/app.blade.php'));
        $this->replaceInFile("@vite('resources/js/app.js')", '@viteReactRefresh'.PHP_EOL."        @vite('resources/js/app.jsx')", resource_path('views/app.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Pages', resource_path('js/Pages'));

        // Tests...
        $this->installTests();

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

        if ($this->option('ssr')) {
            $this->installInertiaReactSsrStack();
        }

        $this->components->info('Breeze scaffolding installed successfully.');
        $this->components->warn('Please execute the [npm install && npm run dev] commands to build your assets.');
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
                $this->output->write($output);
            });

        $this->replaceInFile("'enabled' => false", "'enabled' => true", config_path('inertia.php'));
        $this->replaceInFile('vite build', 'vite build && vite build --ssr', base_path('package.json'));
        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
    }
}
