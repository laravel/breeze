<?php

namespace App\View\Components;

use Illuminate\Support\Arr;
use Illuminate\View\Component;

abstract class Layout extends Component
{
    public $title;

    protected $seperator = '|';

    public function __construct($title = null)
    {
        $title = Arr::wrap($title);

        $title[] = config('app.name', 'Laravel');

        $this->title = implode(" {$this->seperator} ", $title);
    }

    abstract public function render();
}
