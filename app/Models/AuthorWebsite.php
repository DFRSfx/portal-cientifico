<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorWebsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'url',
        'type',
        'label',
        'use_type',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
