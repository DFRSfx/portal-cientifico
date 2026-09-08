<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        "funding_category",
        "project_title",
        "project_description",
        "start_date_year",
        "start_date_month",
        "start_date_day",
        "end_date_year",
        "end_date_month",
        "end_date_day",
        "start_participation_year",
        "start_participation_month",
        "start_participation_day",
        "end_participation_year",
        "end_participation_month",
        "end_participation_day",
        "investigation_role",
        "investigation_role_description",
        "funding_renewable",
        "competitive",
        "status",
        "total_amount",
        "program_name",
        "year_awarded",
        "author_id"
    ];

    public function author()
    {
        $this->belongsTo(Author::class);
    }
    
}
