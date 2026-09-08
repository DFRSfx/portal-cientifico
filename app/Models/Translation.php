<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'series_title',
        'volume',
        'number_of_volumes',
        'edition',
        'number_of_pages',
        'publication_status',
        'publication_year',
        'publisher',
        'publication_location',
        'url',
    ];
    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
