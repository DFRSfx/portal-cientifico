<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CienciaVitaeUpdateProfileButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public int $authorId)
    {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ciencia-vitae-update-profile-button');
    }

    public function shouldRender(): bool
    {
        return isset(auth()->user()->authorInformation) && (auth()->user()->authorInformation->id == $this->authorId);
    }

}
