<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorDegree extends Model
{
    use HasFactory;

    protected $fillable = [
        "degree_type",
        "degree_name",
        "institution_name",
        "degree_major",
        "description",
        "classification",
        "degree_status",
        "research_classification",
        "thesis_title",
        "start_date_year",
        "start_date_month",
        "start_date_day",
        "end_date_year",
        "end_date_month",
        "end_date_day",
        "author_id"
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
