<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncyclopediaEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_title',
        'encyclopedia_title',
        'volume',
        'number_of_volumes',
        'edition',
        'page_range_from',
        'page_range_to',
        'publication_status',
        'publication_year',
        'publisher',
        'publication_location',
        'autoring_role',
        'url',
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
