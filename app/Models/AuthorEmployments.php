<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorEmployments extends Model
{
    use HasFactory;

    protected $fillable = [
        "employment_category",
        "institution_name",
        "position_type",
        "position_title",
        "position_title_group",
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
