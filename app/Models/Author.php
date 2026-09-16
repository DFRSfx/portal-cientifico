<?php

namespace App\Models;

use App\Models\User;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'orcid',
        'id_google_scholar',
        'id_researcher',
        'id_scopus_author',
        'researchgate_profile',
        'id_lattes',
        'h_index',
        'h_index_scholar',
        'citations_scholar',
        'h_index_scopus',
        'citations_scopus',
        'h_index_source',
        'h_index_reported_at',
        'h_index_is_self_declared',
        "resume",
        "profile_updated_date",
        "profile_image_is_public",
        "profile_is_public", // this identifies if a ciencia vitae profile is public or private and is not the same thing as the image because a user may have one public profile and one private image
        "user_id",
    ];

    protected $casts = [
        'h_index_reported_at' => 'date',
        'h_index_is_self_declared' => 'boolean',
    ];

    public function userInformation()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function project()
    {
        return $this->hasMany(Project::class);
    }

    public function activity()
    {
        return $this->belongsToMany(DomainActivitiesTopic::class, "author_domain_activities", foreignPivotKey: "author_id", relatedPivotKey: "topic_id");
    }//activity

    public function emails()
    {
        return $this->hasMany(AuthorEmail::class);
    }

    public function phones()
    {
        return $this->hasMany(AuthorPhone::class);
    }

    public function addresses()
    {
        return $this->hasMany(AuthorAddress::class);
    }

    public function websites()
    {
        return $this->hasMany(AuthorWebsite::class);
    }

    public function citationName()
    {
        return $this->hasMany(AuthorCitationName::class);
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class, "author_spoken_languages")->withPivot(
            "speech_level",
            "writing_level",
            "listening_level",
            "peer_review_level",
            "read-level",
        )->withTimestamps();
    }

    public function output()
    {
        return $this->belongsToMany(Output::class, "author_outputs")->withPivot(
            "citation_id"
        )->withTimestamps();
    }//output

    public function citations()
    {
        return $this->belongsToMany(AuthorCitationName::class, "author_outputs", "author_id","citation_id")->withPivot(
            "output_id"
        )->withTimestamps();
    }

    public function service()
    {
        return $this->hasMany(Service::class);
    }

    public function employments()
    {
        return $this->hasMany(AuthorEmployments::class);
    }

    public function degrees()
    {
        return $this->hasMany(AuthorDegree::class);
    }

    public function distinctions()
    {
        return $this->hasMany(AuthorDistinction::class);
    }

}
