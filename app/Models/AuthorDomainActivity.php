<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorDomainActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'topic_id',
        'author_id'
    ];
    
    public function authors()
    {
        return $this->belongsTo(Author::class);
    }//authors

    public function topic()
    {
        return $this->belongsTo(DomainActivitiesTopic::class);
    }//topic

}
