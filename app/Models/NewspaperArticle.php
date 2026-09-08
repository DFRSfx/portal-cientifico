<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewspaperArticle extends Model
{
    use HasFactory;
    protected $fillable = [
       "article_title",
        "newspaper",
        "section",
        "volume",
        "edition",
        "page_range_from",
        "page_range_to",
        "publication_date",
        "publication_location",
        "url",
        "research_classifications",
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
