<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MdbSpan extends Component
{
    private $colors = ['badge-primary', 'badge-success', 'badge-secondary', 'badge-warning', 'badge-info', 'badge-danger'];

    // Identifies the status and the color position
    private $publicationStatus = ["Published" => 4, "Accepted" => 1, "default" => 5];

    public string $color;

    /**
     * Create a new component instance.
     */
    public function __construct(public string $type, public string $spanValue)
    {
        if($type == "publicationStatus")
        {
            $colorPosition = $this->publicationStatus[$spanValue] ?? $this->publicationStatus["default"];
        }

        $this->color = $this->colors[$colorPosition];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.mdb-span');
    }
}
