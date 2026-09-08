<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceAbstract extends Model
{
    use HasFactory;
    protected $fillable = [
        "article_title",
        "conference_name",
        "volume",
        "issue",
        "page_range_from",
        "page_range_to",
        "publication_date",
        "conference_location",
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
