<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_title',
        'journal',
        'volume',
        'issue',
        'start_page',
        'end_page',
        'city',
        'publisher',
        'refreed',
        'open_access',
        'status',
        "publication_year",
        "publication_monh",
        "publication_day",
        'country',
        'role',
        'url'
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }

}
