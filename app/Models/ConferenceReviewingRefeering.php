<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceReviewingRefeering extends Model
{
    use HasFactory;

    protected $fillable = [
        'conference',
        'conference_host',
        'works_reviewed',
    ];

    public function service()
    {
        return $this->morphOne(Service::class, 'polymorphic', 'service_type_class', 'model_id');
    }

}
