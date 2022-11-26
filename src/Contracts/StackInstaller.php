<?php

namespace Laravel\Breeze\Contracts;

interface StackInstaller
{
    /**
     * Install the Breeze stack.
     *
     * @return void
     */
    public function install(): void;
}
