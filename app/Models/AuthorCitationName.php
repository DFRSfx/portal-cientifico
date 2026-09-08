<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorCitationName extends Model
{
    use HasFactory;

    protected $table = "author_citation_names";

    protected $fillable = [
        'author_id',
        'citation_name',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, "author_outputs", "author_id")->withPivot(
            "output_id"
        )->withTimestamps();
    }

    public function outputs(){
        return $this->belongsToMany(Output::class, "author_outputs", "citation_id")->withPivot(
            "author_id"
        )->withTimestamps();
    }

    
}
