<?php

namespace Laravel\Breeze\Console;

use Illuminate\Console\Command;
use Illuminate\Console\View\Components\Factory;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'breeze:install {stack=blade : The development stack that should be installed (blade,react,vue,api)}
                            {--dark : Indicate that dark mode support should be installed}
                            {--pest : Indicate that Pest should be installed}
                            {--ssr : Indicates if Inertia SSR support should be installed}
                            {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a Breeze stack';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        // dd($this->components);
        // dd(123);

        $stack = $this->argument('stack');
        $stacks = config('breeze.stacks', []);

        if (! array_key_exists($stack, $stacks)) {
            $stacks = array_map(fn ($s) => "[{$s}]", array_keys($stacks));

            sort($stacks);

            $stacksList = implode(', ', $stacks);

            $this->components->error("Invalid stack. Supported stacks are {$stacksList}.");
            return 0;
        }

        $installer = app($stacks[$stack], ['command' => $this]);
        $installer->install();

        return 1;
    }

    public function getComponents(): Factory
    {
        return $this->components;
    }
}
