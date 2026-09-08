<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferencePoster extends Model
{
    use HasFactory;
    protected $fillable = [
        
        "conference_name",
        "role",
        "conference_year",
        "conference_month",
        "conference_day",


    ];


    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }

    
}
