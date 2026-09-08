<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'issue_title',
        "journal",
        'volume',
        'issue_number',
        'number_of_pages',
        'refereed',
        'publication_status',
        'publication_date',
        'publication_location',
        'editing_role',
        'url',
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
