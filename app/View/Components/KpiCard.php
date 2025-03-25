<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class KpiCard extends Component
{
    public $icon;
    public $title;
    public $value;
    public $class;
    /**
     * Create a new component instance.
     */
    public function __construct($icon, $title, $value, $class = 'bg-white')
    {
        $this->icon = $icon;
        $this->title = $title;
        $this->value = $value;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.kpi-card');
    }
}
