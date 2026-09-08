<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrefacePostface extends Model
{
    use HasFactory;

        protected $fillable = [
        'preface_postface_type',
        'preface_postface_title',
        'book_title',
        'book_volume',
        'book_edition',
        'preface_postface_page_range_from',
        'preface_postface_page_range_to',
        'refereed',
        'publication_status',
        'publication_year',
        'publication_location',
        'book_publisher',
        'authoring_role',
        'url'
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
