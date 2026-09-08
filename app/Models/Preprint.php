<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'volume',
        'journal',
        'submission_location',
        'date_submitted',
        'url',
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
