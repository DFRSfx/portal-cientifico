<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class DomainActivitiesTopic extends Model
{
    use HasFactory;

    protected $fillable = ["topic_name"];

    protected $table = "domain_activities_topics";

    public function author()
    {
        return $this->belongsToMany(Author::class, foreignPivotKey: "author_id", relatedPivotKey: "topic_id")->using(AuthorDomainActivity::class);
    }//topic

}
