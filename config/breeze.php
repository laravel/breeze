<?php

use Laravel\Breeze\Installers\ApiStack;
use Laravel\Breeze\Installers\BladeStack;
use Laravel\Breeze\Installers\InertiaVueStack;
use Laravel\Breeze\Installers\InertiaReactStack;

return [

    'stacks' => [
        'vue' => InertiaVueStack::class,
        'react' => InertiaReactStack::class,
        'blade' => BladeStack::class,
        'api' => ApiStack::class,
    ],

];
