<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'volume',
        'status',
        'pages_number',
        'url',
        'pub_country',
        'pub_city',
        'edition',
        'refreed',
        'publisher',
        'publication_year',
        'role'
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }

}
