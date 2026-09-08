<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        "language"
    ];


    /**
     * The users that belong to the role.
     */
    public function author()
    {
        return $this->belongsToMany(Author::class)->using(AuthorSpokenLanguage::class)->withPivot(
            "speech_level",
            "writing_level",
            "listening_level",
            "peer_review_level",
            "read-level",
        )->withTimestamps();
    }
}
