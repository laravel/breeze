<?php

namespace Laravel\Breeze\Console;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

trait InstallsInertiaStacks
{
    /**
     * Install the Inertia Vue Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaVueStack()
    {
        // Install Inertia...
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^1.0', 'laravel/sanctum:^4.0', 'tightenco/ziggy:^2.0'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@inertiajs/vue3' => '^1.0.0',
                '@tailwindcss/forms' => '^0.5.3',
                '@vitejs/plugin-vue' => '^5.0.0',
                'autoprefixer' => '^10.4.12',
                'postcss' => '^8.4.31',
                'tailwindcss' => '^3.2.1',
                'vue' => '^3.4.0',
            ] + $packages;
        });

        if ($this->option('typescript')) {
            $this->updateNodePackages(function ($packages) {
                return [
                    'typescript' => '^5.5.3',
                    'vue-tsc' => '^2.0.24',
                ] + $packages;
            });
        }

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers', app_path('Http/Controllers'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // Middleware...
        $this->installMiddleware([
            '\App\Http\Middleware\HandleInertiaRequests::class',
            '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class',
        ]);

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-vue/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        @unlink(resource_path('views/welcome.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        if ($this->option('typescript')) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue-ts/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue-ts/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue-ts/resources/js/Pages', resource_path('js/Pages'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue-ts/resources/js/types', resource_path('js/types'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-vue/resources/js/Pages', resource_path('js/Pages'));
        }

        if (! $this->option('dark')) {
            $this->removeDarkClasses((new Finder)
                ->in(resource_path('js'))
                ->name('*.vue')
                ->notName('Welcome.vue')
            );
        }

        // Tests...
        if (! $this->installTests()) {
            return 1;
        }

        if ($this->option('pest')) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/pest-tests/Feature', base_path('tests/Feature'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/tests/Feature', base_path('tests/Feature'));
        }

        // Routes...
        copy(__DIR__.'/../../stubs/inertia-common/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/inertia-common/routes/auth.php', base_path('routes/auth.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/inertia-vue/vite.config.js', base_path('vite.config.js'));

        if ($this->option('typescript')) {
            copy(__DIR__.'/../../stubs/inertia-vue-ts/tsconfig.json', base_path('tsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-vue-ts/resources/js/app.ts', resource_path('js/app.ts'));

            if (file_exists(resource_path('js/app.js'))) {
                unlink(resource_path('js/app.js'));
            }

            if (file_exists(resource_path('js/bootstrap.js'))) {
                rename(resource_path('js/bootstrap.js'), resource_path('js/bootstrap.ts'));
            }

            $this->replaceInFile('"vite build', '"vue-tsc && vite build', base_path('package.json'));
            $this->replaceInFile('.js', '.ts', base_path('vite.config.js'));
            $this->replaceInFile('.js', '.ts', resource_path('views/app.blade.php'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-common/jsconfig.json', base_path('jsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-vue/resources/js/app.js', resource_path('js/app.js'));
        }

        if ($this->option('ssr')) {
            $this->installInertiaVueSsrStack();
        }

        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
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
                '@vue/server-renderer' => '^3.4.0',
            ] + $packages;
        });

        if ($this->option('typescript')) {
            copy(__DIR__.'/../../stubs/inertia-vue-ts/resources/js/ssr.ts', resource_path('js/ssr.ts'));
            $this->replaceInFile("input: 'resources/js/app.ts',", "input: 'resources/js/app.ts',".PHP_EOL."            ssr: 'resources/js/ssr.ts',", base_path('vite.config.js'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-vue/resources/js/ssr.js', resource_path('js/ssr.js'));
            $this->replaceInFile("input: 'resources/js/app.js',", "input: 'resources/js/app.js',".PHP_EOL."            ssr: 'resources/js/ssr.js',", base_path('vite.config.js'));
        }

        $this->configureZiggyForSsr();

        $this->replaceInFile('vite build', 'vite build && vite build --ssr', base_path('package.json'));
        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
    }

    /**
     * Install the Inertia React Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaReactStack()
    {
        // Install Inertia...
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^1.0', 'laravel/sanctum:^4.0', 'tightenco/ziggy:^2.0'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@headlessui/react' => '^2.0.0',
                '@inertiajs/react' => '^1.0.0',
                '@tailwindcss/forms' => '^0.5.3',
                '@vitejs/plugin-react' => '^4.2.0',
                'autoprefixer' => '^10.4.12',
                'postcss' => '^8.4.31',
                'tailwindcss' => '^3.2.1',
                'react' => '^18.2.0',
                'react-dom' => '^18.2.0',
            ] + $packages;
        });

        if ($this->option('typescript')) {
            $this->updateNodePackages(function ($packages) {
                return [
                    '@types/node' => '^18.13.0',
                    '@types/react' => '^18.0.28',
                    '@types/react-dom' => '^18.0.10',
                    'typescript' => '^5.0.2',
                ] + $packages;
            });
        }

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers', app_path('Http/Controllers'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // Middleware...
        $this->installMiddleware([
            '\App\Http\Middleware\HandleInertiaRequests::class',
            '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class',
        ]);

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-react/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        @unlink(resource_path('views/welcome.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        if ($this->option('typescript')) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/js/Pages', resource_path('js/Pages'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react-ts/resources/js/types', resource_path('js/types'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-react/resources/js/Pages', resource_path('js/Pages'));
        }

        if (! $this->option('dark')) {
            $this->removeDarkClasses((new Finder)
                ->in(resource_path('js'))
                ->name(['*.jsx', '*.tsx'])
                ->notName(['Welcome.jsx', 'Welcome.tsx'])
            );
        }

        // Tests...
        if (! $this->installTests()) {
            return 1;
        }

        if ($this->option('pest')) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/pest-tests/Feature', base_path('tests/Feature'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/tests/Feature', base_path('tests/Feature'));
        }

        // Routes...
        copy(__DIR__.'/../../stubs/inertia-common/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/inertia-common/routes/auth.php', base_path('routes/auth.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/inertia-react/vite.config.js', base_path('vite.config.js'));

        if ($this->option('typescript')) {
            copy(__DIR__.'/../../stubs/inertia-react-ts/tsconfig.json', base_path('tsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-react-ts/resources/js/app.tsx', resource_path('js/app.tsx'));

            if (file_exists(resource_path('js/bootstrap.js'))) {
                rename(resource_path('js/bootstrap.js'), resource_path('js/bootstrap.ts'));
            }

            $this->replaceInFile('"vite build', '"tsc && vite build', base_path('package.json'));
            $this->replaceInFile('.jsx', '.tsx', base_path('vite.config.js'));
            $this->replaceInFile('.jsx', '.tsx', resource_path('views/app.blade.php'));
            $this->replaceInFile('.vue', '.tsx', base_path('tailwind.config.js'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-common/jsconfig.json', base_path('jsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-react/resources/js/app.jsx', resource_path('js/app.jsx'));

            $this->replaceInFile('.vue', '.jsx', base_path('tailwind.config.js'));
        }

        if (file_exists(resource_path('js/app.js'))) {
            unlink(resource_path('js/app.js'));
        }

        if ($this->option('ssr')) {
            $this->installInertiaReactSsrStack();
        }

        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');
    }

    /**
     * Install the Inertia Svelte Breeze stack.
     *
     * @return int|null
     */
    protected function installInertiaSvelteStack()
    {
        // Install Inertia...
        if (! $this->requireComposerPackages(['inertiajs/inertia-laravel:^1.0', 'laravel/sanctum:^4.0', 'tightenco/ziggy:^2.0'])) {
            return 1;
        }

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@tailwindcss/forms' => '^0.5.3',
                'autoprefixer' => '^10.4.12',
                'laravel-vite-plugin' => '^1.0',
                'postcss' => '^8.4.31',
                'tailwindcss' => '^3.2.1',
                'vite' => '^5.0',
            ] + $packages;
        });
        $this->updateNodePackages(function ($packages) {
            return [
                'axios' => '^1.6.4',
                '@inertiajs/svelte' => '^1.2.0',
                '@sveltejs/vite-plugin-svelte' => '^3.1.1',
                'concurrently' => '^8.2.2',
                'ziggy-js' => '^2.2.1',
                'minimist' => '^1.2.8',
            ] + $packages;
        }, false);

        if ($this->option('typescript')) {
            $this->updateNodePackages(function ($packages) {
                return [
                    '@types/node' => '^18.13.0',
                    'svelte-check' => '^3.8.4',
                    'svelte-preprocess' => '^6.0.2',
                    'svelte2tsx' => '^0.7.13',
                    'ts-loader' => '^9.5.1',
                    'ts-node' => '^10.9.2',
                    '@tsconfig/svelte' => '^5.0.4',
                    'typescript' => '^5.0.2',
                ] + $packages;
            });
        }

        // Controllers...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/app/Http/Controllers', app_path('Http/Controllers'));

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/default/app/Http/Requests', app_path('Http/Requests'));

        // Middleware...
        $this->installMiddleware([
            '\App\Http\Middleware\HandleInertiaRequests::class',
            '\Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class',
        ]);

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Middleware'));
        copy(__DIR__.'/../../stubs/inertia-common/app/Http/Middleware/HandleInertiaRequests.php', app_path('Http/Middleware/HandleInertiaRequests.php'));

        // Views...
        copy(__DIR__.'/../../stubs/inertia-svelte/resources/views/app.blade.php', resource_path('views/app.blade.php'));

        @unlink(resource_path('views/welcome.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));

        if ($this->option('typescript')) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/Pages', resource_path('js/Pages'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/types', resource_path('js/types'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Components', resource_path('js/Components'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Layouts', resource_path('js/Layouts'));
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-svelte/resources/js/Pages', resource_path('js/Pages'));
        }

        if (! $this->option('dark')) {
            $this->removeDarkClasses((new Finder)
                ->in(resource_path('js'))
                ->name(['*.js', '*.ts'])
                ->notName(['Welcome.js', 'Welcome.ts'])
            );
        }

        // Tests...
        if (! $this->installTests()) {
            return 1;
        }

        if ($this->option('pest')) {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/pest-tests/Feature', base_path('tests/Feature'));
        } else {
            (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/inertia-common/tests/Feature', base_path('tests/Feature'));
        }

        // Routes...
        copy(__DIR__.'/../../stubs/inertia-common/routes/web.php', base_path('routes/web.php'));
        copy(__DIR__.'/../../stubs/inertia-common/routes/auth.php', base_path('routes/auth.php'));

        // Tailwind / Vite...
        copy(__DIR__.'/../../stubs/default/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/default/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/inertia-common/tailwind.config.js', base_path('tailwind.config.js'));
        $prefix = '';
        if ($this->option('typescript')) {
            $prefix = '-ts';
        }
        if ($this->option('ssr')) {
            copy(__DIR__.'/../../stubs/inertia-svelte'.$prefix.'/vite.config.ssr.js', base_path('vite.config.js'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-svelte'.$prefix.'/vite.config.js', base_path('vite.config.js'));
        }

        if (file_exists(resource_path('js/app.js'))) {
            unlink(resource_path('js/app.js'));
        }

        if ($this->option('typescript')) {
            copy(__DIR__.'/../../stubs/inertia-svelte-ts/tsconfig.json', base_path('tsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-svelte-ts/tsconfig.node.json', base_path('tsconfig.node.json'));
            copy(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/app.ts', resource_path('js/app.ts'));
            copy(__DIR__.'/../../stubs/inertia-svelte-ts/resources/js/route.ts', resource_path('js/route.ts'));

            if (file_exists(resource_path('js/bootstrap.js'))) {
                rename(resource_path('js/bootstrap.js'), resource_path('js/bootstrap.ts'));
            }

            $this->replaceInFile('vite build', 'php artisan ziggy:generate && tsc && vite build', base_path('package.json'));
            $this->replaceInFile('.js', '.ts', base_path('vite.config.js'));
            $this->replaceInFile('.js', '.ts', resource_path('views/app.blade.php'));
            $this->replaceInFile('.vue', '.svelte', base_path('tailwind.config.js'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-common/jsconfig.json', base_path('jsconfig.json'));
            copy(__DIR__.'/../../stubs/inertia-svelte/resources/js/app.js', resource_path('js/app.js'));
            copy(__DIR__.'/../../stubs/inertia-svelte/resources/js/route.js', resource_path('js/route.js'));

            $this->replaceInFile('.vue', '.svelte', base_path('tailwind.config.js'));
        }

        $this->installInertiaSvelteSsrStack($this->option('ssr'));

        $this->components->info('Installing and building Node dependencies.');

        if (file_exists(base_path('pnpm-lock.yaml'))) {
            $this->runCommands(['pnpm install', 'pnpm run build']);
        } elseif (file_exists(base_path('yarn.lock'))) {
            $this->runCommands(['yarn install', 'yarn run build']);
        } elseif (file_exists(base_path('bun.lockb'))) {
            $this->runCommands(['bun install', 'bun run build']);
        } else {
            $this->runCommands(['npm install', 'npm run build']);
        }

        $this->line('');
        $this->components->info('Breeze scaffolding installed successfully.');

        return 1;
    }

    /**
     * Install the Inertia React SSR stack into the application.
     *
     * @return void
     */
    protected function installInertiaReactSsrStack()
    {
        if ($this->option('typescript')) {
            copy(__DIR__.'/../../stubs/inertia-react-ts/resources/js/ssr.tsx', resource_path('js/ssr.tsx'));
            $this->replaceInFile("input: 'resources/js/app.tsx',", "input: 'resources/js/app.tsx',".PHP_EOL."            ssr: 'resources/js/ssr.tsx',", base_path('vite.config.js'));
            $this->configureReactHydrateRootForSsr(resource_path('js/app.tsx'));
        } else {
            copy(__DIR__.'/../../stubs/inertia-react/resources/js/ssr.jsx', resource_path('js/ssr.jsx'));
            $this->replaceInFile("input: 'resources/js/app.jsx',", "input: 'resources/js/app.jsx',".PHP_EOL."            ssr: 'resources/js/ssr.jsx',", base_path('vite.config.js'));
            $this->configureReactHydrateRootForSsr(resource_path('js/app.jsx'));
        }

        $this->configureZiggyForSsr();

        $this->replaceInFile('vite build', 'vite build && vite build --ssr', base_path('package.json'));
        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
    }

    /**
     * Install the Inertia Svelte SSR stack into the application.
     *
     * @return void
     */
    protected function installInertiaSvelteSsrStack($ssr)
    {
        $ext = 'js';
        if ($this->option('typescript')) {
            $ext = 'ts';
        }
        $prefix = '';
        if ($this->option('typescript')) {
            $prefix = '-ts';
        }

        if ($ssr) {
            copy(__DIR__.'/../../stubs/inertia-svelte'.$prefix.'/resources/js/ssr/route-factory.dev.'.$ext, resource_path('js/route-factory.dev.'.$ext));
            copy(__DIR__.'/../../stubs/inertia-svelte'.$prefix.'/resources/js/ssr/route-factory.prod.'.$ext, resource_path('js/route-factory.prod.'.$ext));
            if ($this->option('typescript')) {
                $this->replaceInFile('tsc && vite build', 'tsc && vite build && vite build --ssr', base_path('package.json'));
            } else {
                $this->replaceInFile('vite build', 'vite build && vite build --ssr', base_path('package.json'));
            }
        } else {
            copy(__DIR__.'/../../stubs/inertia-svelte'.$prefix.'/resources/js/no-ssr/route-factory.'.$ext, resource_path('js/route-factory.'.$ext));
        }
        $scripts = [
            'start' => 'concurrently --kill-others "npm run dev" "php artisan serve"',
            'start-w-ssr' => 'npm run build && concurrently --kill-others "php artisan serve" "php artisan inertia:start-ssr"',
        ];
        if (! $ssr) {
            unset($scripts['start-w-ssr']);
        }

        $packagePath = base_path('package.json');
        $packageJson = json_decode(file_get_contents($packagePath), true);

        foreach ($scripts as $scriptName => $scriptValue) {
            $packageJson['scripts'][$scriptName] = $scriptValue;
        }
        file_put_contents($packagePath, json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        if (! $ssr) {
            return;
        }
        copy(__DIR__.'/../../stubs/inertia-svelte'.$prefix.'/resources/js/ssr.'.$ext, resource_path('js/ssr.'.$ext));
        $this->replaceInFile("input: 'resources/js/app.'.$ext,", "input: 'resources/js/app.'.$ext,".PHP_EOL."            ssr: 'resources/js/ssr.$ext',", base_path('vite.config.js'));
        $this->configureSvelteHydrateRootForSsr(resource_path('js/app.'.$ext));

        $this->configureZiggyForSsr();

        $this->replaceInFile('/node_modules', '/bootstrap/ssr'.PHP_EOL.'/node_modules', base_path('.gitignore'));
    }

    /**
     * Configure the application JavaScript file to utilize hydrateRoot for SSR.
     *
     * @param  string  $path
     * @return void
     */
    protected function configureReactHydrateRootForSsr($path)
    {
        $this->replaceInFile(
            <<<'EOT'
            import { createRoot } from 'react-dom/client';
            EOT,
            <<<'EOT'
            import { createRoot, hydrateRoot } from 'react-dom/client';
            EOT,
            $path
        );

        $this->replaceInFile(
            <<<'EOT'
                    const root = createRoot(el);

                    root.render(<App {...props} />);
            EOT,
            <<<'EOT'
                    if (import.meta.env.DEV) {
                        createRoot(el).render(<App {...props} />);
                        return
                    }

                    hydrateRoot(el, <App {...props} />);
            EOT,
            $path
        );
    }

    /**
     * Configure the application JavaScript file to utilize hydrateRoot for SSR.
     *
     * @param  string  $path
     * @return void
     */
    protected function configureSvelteHydrateRootForSsr($path)
    {
        $this->replaceInFile(
            <<<'EOT'
                    new App({ target: el, props });
            EOT,
            <<<'EOT'
                    new App({ target: el, props, hydrate: true });
            EOT,
            $path
        );
    }

    /**
     * Configure Ziggy for SSR.
     *
     * @return void
     */
    protected function configureZiggyForSsr()
    {
        $this->replaceInFile(
            <<<'EOT'
            use Inertia\Middleware;
            EOT,
            <<<'EOT'
            use Inertia\Middleware;
            use Tighten\Ziggy\Ziggy;
            EOT,
            app_path('Http/Middleware/HandleInertiaRequests.php')
        );

        $this->replaceInFile(
            <<<'EOT'
                        'auth' => [
                            'user' => $request->user(),
                        ],
            EOT,
            <<<'EOT'
                        'auth' => [
                            'user' => $request->user(),
                        ],
                        'ziggy' => fn () => [
                            ...(new Ziggy)->toArray(),
                            'location' => $request->url(),
                        ],
            EOT,
            app_path('Http/Middleware/HandleInertiaRequests.php')
        );

        if ($this->option('typescript')) {
            $this->replaceInFile(
                <<<'EOT'
                export interface User {
                EOT,
                <<<'EOT'
                import { Config } from 'ziggy-js';

                export interface User {
                EOT,
                resource_path('js/types/index.d.ts')
            );

            $this->replaceInFile(
                <<<'EOT'
                    auth: {
                        user: User;
                    };
                EOT,
                <<<'EOT'
                    auth: {
                        user: User;
                    };
                    ziggy: Config & { location: string };
                EOT,
                resource_path('js/types/index.d.ts')
            );
        }
    }
}
