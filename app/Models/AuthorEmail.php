<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        "email"
    ];

    public function authors()
    {
        return $this->belongsTo(Author::class);
    }
    
}
