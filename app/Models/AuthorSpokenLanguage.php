<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
class AuthorSpokenLanguage extends Pivot
{
    use HasFactory;

    protected $primaryKey = ['language_id', 'author_id'];
    
    public $incrementing = false;

    protected $fillable = [
        "language_id",
        "author_id",
        "speech_level",
        "writing_level",
        "listening_level",
        "peer_review_level",
        "read-level"
    ];
}
