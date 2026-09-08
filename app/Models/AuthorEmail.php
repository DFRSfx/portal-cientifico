<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        "author_id",
        "email",
        "use_type"
    ];

    public function authors()
    {
        return $this->belongsTo(Author::class);
    }
    
}
