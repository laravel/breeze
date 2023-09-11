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
        // ...

        $this->line('');
        $this->components->info('Livewire scaffolding installed successfully.');
    }
}
