<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AlertComponent extends Component
{
    public $color;
    public $iconClass;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public bool $errorMessage,
        public string $message,
        public string $id)
    {
        
        if($errorMessage)
        {
            $this->color = "danger";
            $this->iconClass = "fas fa-times-circle me-3";
        }
        else
        {
            $this->iconClass = "fas fa-check me-2";
            $this->color = "success";
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert-component');
    }
}
