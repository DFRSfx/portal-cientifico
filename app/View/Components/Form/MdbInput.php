<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MdbInput extends Component
{
    /**
     * Create a new component instance.
     */

    public function __construct(
        public string $type,
        public string $name,
        public string $value
    ) {
        //
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.mdb-input');
    }
}
