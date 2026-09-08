<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalReviewingRefeering extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal',
        'press',
        'works_reviewed',
        'url',
    ];

    public function service()
    {
        return $this->morphOne(Service::class, 'polymorphic', 'service_type_class', 'model_id');
    }
}
