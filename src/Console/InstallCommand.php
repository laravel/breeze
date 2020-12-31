<?php

namespace Laravel\Breeze\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'breeze:install {guard=web} {passwords=users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Breeze controllers and resources';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The name of the guard.
     *
     * @var string
     */
    protected $guardName;

    /**
     * The name of the password broker.
     *
     * @var string
     */
    protected $brokerName;

    /**
     * The controller save path.
     *
     * @var string
     */
    protected $controllerPath;

    /**
     * The controller namespace.
     *
     * @var string
     */
    protected $controllerNamespace;

    /**
     * The request save path.
     *
     * @var string
     */
    protected $requestPath;

    /**
     * The views save path.
     *
     * @var string
     */
    protected $viewsPath;

    /**
     * The routes prefix.
     *
     * @var string
     */
    protected $routePrefix;

    /**
     * The routes name prefix.
     *
     * @var string
     */
    protected $routeNamePrefix;

    /**
     * The guard name to pass to the "guest" middleware.
     *
     * @var string
     */
    protected $routeGuestGuardName;

    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->setProperties();

        // NPM Packages...
        $this->updateNodePackages(function ($packages) {
            return [
                '@tailwindcss/forms' => '^0.2.1',
                'alpinejs' => '^2.7.3',
                'postcss-import' => '^12.0.1',
                'tailwindcss' => 'npm:@tailwindcss/postcss7-compat@^2.0.1',
                'autoprefixer' => '^9.8.6',
            ] + $packages;
        });

        $this->generateControllers();
        $this->generateRequests();
        $this->generateRoutes();
        $this->generateViews();
        $this->publishAssets();
        $this->publishTests();

        if ($this->guardName === 'web') {
            // "Dashboard" Route...
            $this->replaceInFile('/home', '/dashboard', resource_path('views/welcome.blade.php'));
            $this->replaceInFile('Home', 'Dashboard', resource_path('views/welcome.blade.php'));
            $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));
        }

        $this->info('Breeze scaffolding installed successfully.');
        $this->comment('Please execute the "npm install && npm run dev" command to build your assets.');
    }

    private function setProperties()
    {
        $this->guardName = $this->argument('guard');
        $this->brokerName = $this->argument('passwords');

        $this->controllerNamespace = ($this->guardName === 'web')
            ? 'App\Http\Controllers\Auth'
            : sprintf('App\Http\Controllers\Auth\%s', Str::title($this->guardName));

        $this->controllerPath = ($this->guardName === 'web')
            ? app_path('Http/Controllers/Auth/')
            : sprintf(app_path('Http/Controllers/Auth/%s/'), Str::title($this->guardName));

        $this->requestPath = ($this->guardName === 'web')
            ? app_path('Http/Requests/Auth/')
            : sprintf(app_path('Http/Requests/Auth/%s/'), Str::title($this->guardName));

        $this->viewsPath = ($this->guardName === 'web')
            ? resource_path('views')
            : sprintf(resource_path('views/%s'), Str::lower($this->guardName));

        $this->routePrefix = ($this->guardName === 'web')
            ? '/'
            : sprintf('/%s', Str::plural(Str::lower($this->guardName)));

        $this->routeNamePrefix = ($this->guardName === 'web')
            ? ''
            : sprintf('%s.', Str::plural(Str::lower($this->guardName)));

        $this->routeGuestGuardName = ($this->guardName === 'web')
            ? ''
            : sprintf(':%s', $this->guardName);
    }

    /**
     * Generate the authentication library controllers for the given config.
     *
     * @return void
     */
    protected function generateControllers()
    {
        $controllers = [
            'AuthenticatedSessionController',
            'ConfirmablePasswordController',
            'EmailVerificationNotificationController',
            'EmailVerificationPromptController',
            'NewPasswordController',
            'PasswordResetLinkController',
            'RegisteredUserController',
            'VerifyEmailController',
        ];

        $this->files->ensureDirectoryExists($this->controllerPath);

        foreach ($controllers as $controller) {
            $stubPath = realpath(__DIR__.'/../../stubs/App/Http/Controllers/Auth/'.$controller.'.stub');
            $savePath = $this->controllerPath.$controller.'.php';
            $this->files->put($savePath, $this->buildClass($stubPath));
        }
    }

    /**
     * Generate the request.
     *
     * @return void
     */
    protected function generateRequests()
    {
        $requests = ['LoginRequest'];

        $this->files->ensureDirectoryExists($this->requestPath);

        foreach ($requests as $request) {
            $stubPath = realpath(__DIR__.'/../../stubs/App/Http/Requests/Auth/'.$request.'.stub');
            $savePath = $this->requestPath.$request.'.php';
            $this->files->put($savePath, $this->buildClass($stubPath));
        }
    }

    /**
     * Generate the routes, prefixing anf grouping as required.
     *
     * @return void
     */
    protected function generateRoutes()
    {
        $stubPath = realpath(__DIR__.'/../../stubs/routes/auth.php');
        $savePath = base_path('routes/auth.php');
        $this->files->put($savePath, $this->buildClass($stubPath));

        $this->files->copy(__DIR__.'/../../stubs/routes/web.php', base_path('routes/web.php'));
    }

    /**
     * Generate views that need customisation for guards and route names.
     *
     * @return void
     */
    protected function generateViews()
    {
        $this->copyViews();

        $views = [
            'auth/confirm-password',
            'auth/forgot-password',
            'auth/login',
            'auth/register',
            'auth/reset-password',
            'auth/verify-email',
            'layouts/navigation',
        ];

        $this->files->ensureDirectoryExists(sprintf('%s/auth', $this->viewsPath));
        $this->files->ensureDirectoryExists(sprintf('%s/layouts', $this->viewsPath));

        foreach ($views as $view) {
            $stubPath = realpath(__DIR__.'/../../stubs/resources/views/'.$view.'.blade.php');
            $savePath = $this->viewsPath.$view.'.blade.php';
            $this->files->put($savePath, $this->buildClass($stubPath));
        }
    }

    /**
     * Copy all views and view components that do not need to be populated.
     *
     * @return void
     */
    protected function copyViews()
    {
        $this->files->ensureDirectoryExists($this->viewsPath);
        $this->files->ensureDirectoryExists(resource_path('views/components'));
        $this->files->copyDirectory(__DIR__.'/../../stubs/resources/views/components', resource_path('views/components'));
        $this->files->copy(__DIR__.'/../../stubs/resources/views/dashboard.blade.php', sprintf('%s/dashboard.blade.php', $this->viewsPath));

        $this->files->ensureDirectoryExists(app_path('View/Components'));
        $this->files->copyDirectory(__DIR__.'/../../stubs/App/View/Components', app_path('View/Components'));
    }

    /**
     * Publish tailwind and webpack assets.
     *
     * @return void
     */
    protected function publishAssets()
    {
        $this->files->copy(__DIR__.'/../../stubs/tailwind.config.js', base_path('tailwind.config.js'));
        $this->files->copy(__DIR__.'/../../stubs/webpack.mix.js', base_path('webpack.mix.js'));
        $this->files->copy(__DIR__.'/../../stubs/resources/css/app.css', resource_path('css/app.css'));
        $this->files->copy(__DIR__.'/../../stubs/resources/js/app.js', resource_path('js/app.js'));
    }

    /**
     * Publish tests.
     *
     * @return void
     */
    protected function publishTests()
    {
        $this->files->copyDirectory(__DIR__.'/../../stubs/tests/Feature', base_path('tests/Feature'));
    }

    /**
     * Build the class files by substituting placeholders.
     *
     * @param  string  $filePath
     * @return string
     */
    protected function buildClass(string $filePath)
    {
        return $this->substitute($this->files->get($filePath));
    }

    /**
     * Read the $contents and replace placeholders with real values.
     *
     * @param  string  $contents
     * @return string
     */
    protected function substitute(string $contents)
    {
        return str_replace([
            'DummyGuardName',
            'DummyBrokerName',
            'DummyControllerNamespace',
            'DummyRoutePrefix',
            'DummyRouteNamePrefix',
            'DummyRouteGuestMiddlewareGuard',
        ], [
            $this->guardName,
            $this->brokerName,
            $this->controllerNamespace,
            $this->routePrefix,
            $this->routeNamePrefix,
            $this->routeGuestGuardName,
        ], $contents);
    }

    /**
     * Update the "package.json" file.
     *
     * @param  callable  $callback
     * @param  bool  $dev
     * @return void
     */
    protected static function updateNodePackages(callable $callback, $dev = true)
    {
        if (! file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL
        );
    }

    /**
     * Delete the "node_modules" directory and remove the associated lock files.
     *
     * @return void
     */
    protected static function flushNodeModules()
    {
        tap(new Filesystem, function ($files) {
            $files->deleteDirectory(base_path('node_modules'));

            $files->delete(base_path('yarn.lock'));
            $files->delete(base_path('package-lock.json'));
        });
    }

    /**
     * Replace a given string within a given file.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
