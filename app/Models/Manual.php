<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    use HasFactory;

    protected $fillable = [
        "output_id",
        "title",
        "series_title",
        "volume",
        "number_of_volumes",
        "edition",
        "number_of_pages",
        "publication_status",
        "publication_year",
        "publication_location",
        "publisher",
        "authoring_role",
        "url",
    ];

    public function output()
    {
        return $this->morphOne(Output::class, "publication");
    }
}
