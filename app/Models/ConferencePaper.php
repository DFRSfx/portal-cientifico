<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ConferencePaper extends Model
{
    use HasFactory;

    protected $fillable = [
        'paper_title',
        'conference_name',
        "presentation_year",
        "presentation_month",
        "presentation_day",
        "conference_year",
        "conference_month",
        "conference_day",
        'conf_country',
        'conf_city',
        'proceedings_title',
        'start_page',
        'end_page',
        'status',
        'pub_country',
        'pub_city',
        'publisher',
        'role'
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }

}
