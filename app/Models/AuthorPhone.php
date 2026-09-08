<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'phone_number',
        'type',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
