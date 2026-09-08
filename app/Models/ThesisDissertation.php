<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisDissertation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'volumes_number',
        'institutions_list',
        'degree_type',
        'classification',
        'date',
        'url',
        'supervisors',
        'completionDate',
        'researchClassifications',
        'keywords'
    ];
    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
