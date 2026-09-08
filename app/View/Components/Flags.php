<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

class Flags extends Component
{

    public $currentFlag;

    public $appLanguage;

    /**
     * Create a new component instance.
     */
    public function __construct(public array $flags, )
    {
        $this->appLanguage = App::getLocale();

        $this->currentFlag = $flags[$this->appLanguage]["flag"] ?? "flag flag-united-kingdom";
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.flags');
    }
}
