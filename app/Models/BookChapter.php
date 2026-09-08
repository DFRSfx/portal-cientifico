<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookChapter extends Model
{
    use HasFactory;
    protected $fillable = [
        "output_id",
        "book_title",
        "book_volume",
        "book_edition",
        "chapter_start_page",
        "chapter_end_page",
        "refreed",
        "publisher",
        "status",
        "publication_year",
        "publication_month",
        "publication_day",
        'role',
        'url',
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
