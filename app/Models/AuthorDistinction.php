<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorDistinction extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'distinction_type',
        'distinction_name',
        'effective_year',
        'effective_month',
        'effective_day',
        'institution_name',
        'country',
        'description',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
