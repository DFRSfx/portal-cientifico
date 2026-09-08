<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExhibitionCatalogue extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'number_of_pages',
        'publication_year',
        'gallery_or_publisher',
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}

