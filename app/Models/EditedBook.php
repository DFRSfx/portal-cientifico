<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditedBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'volume',
        'edition',
        'number_of_pages',
        'refereed',
        'status',
        'publication_year',
        'publisher',
        'pub_country',
        'pub_city',
        'editing_role',
        'url',
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
