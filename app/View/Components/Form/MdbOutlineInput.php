<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MdbOutlineInput extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type,
        public string $name,
        public string $value,
        public bool $label,
        public string $labelValue
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.mdb-outline-input');
    }
}
