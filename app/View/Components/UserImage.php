<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class UserImage extends Component
{

    public bool $profileImageIsPublic = false;

    /**
     * Create a new component instance.
     */
    public function __construct(public bool $showLogedUserImage, public $cienciaVitae = null, public bool $cienciaVitaeImageIsPublic = true)
    {

        if ($showLogedUserImage && Auth::check() && auth()->user()->authorInformation->profile_image_is_public) 
        {
            $this->profileImageIsPublic = true;
            
            $this->cienciaVitae = auth()->user()->ciencia_vitae;
        }
        else if(isset($cienciaVitae) && $cienciaVitaeImageIsPublic)
        {
            $this->profileImageIsPublic = true;
            
            $this->cienciaVitae = $cienciaVitae;
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.user-image');
    }
}
