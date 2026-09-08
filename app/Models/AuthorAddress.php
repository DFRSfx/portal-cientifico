<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
