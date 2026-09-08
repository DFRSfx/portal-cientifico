<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        "output_id",
        "report_title",
        "volume",
        "number_of_pages",
        "institution",
        "date_submitted",
        "authoring_role",
        "publication_status",
        "url",
    ];

    public function output()
    {
        return $this->morphOne(Output::class, "publication");
    }
}
