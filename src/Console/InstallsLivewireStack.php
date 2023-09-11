<?php

namespace Laravel\Breeze\Console;

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
        // Install Livewire...
        if (! $this->requireComposerPackages(['livewire/livewire:^3.0', 'livewire/volt:^1.0'])) {
            return 1;
        }

        $this->line('');
        $this->components->info('Livewire scaffolding installed successfully.');
    }
}
