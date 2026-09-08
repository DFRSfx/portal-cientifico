<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class DateComponent extends Component
{
    public string $dateToShow = "";

    /**
     * Create a new component instance.
     */
    public function __construct(public Model $model, public string $initialDateAttribute, public string $finalDateAttribute)
    {
        
        $initialDate = $this->formatDate(true);
        $finalDate = $this->formatDate();

        if($initialDate && $finalDate)
        {
            $this->dateToShow = $initialDate . ' - ' . $finalDate;
        }
        else
        {
            $this->dateToShow = $finalDate;
        }

    }

    private function formatDate($initialDate = false)
    {

        if($initialDate)
        {
            $dateAttribute = $this->initialDateAttribute;

            $date = "";
        }
        else
        {
            $dateAttribute = $this->finalDateAttribute;

            $date = "Atual";
        }

        if (isset($this->model->{$dateAttribute . "_year"}) && isset($this->model->{$dateAttribute . "_month"}) && isset($this->model->{$dateAttribute . "_day"}))
        {
            $date = $this->model->{$dateAttribute . "_year"} . '/' . $this->model->{$dateAttribute . "_month"} . '/' . $this->model->{$dateAttribute . "_day"};
        }
        else if(isset($this->model->end_date_year))
        {
            $date = $this->model->{$dateAttribute . "_year"};
        }
        
        return $date;
    }

   

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.date-component');
    }
}
