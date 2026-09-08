<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvUpdateRun extends Model
{
    protected $fillable = [
        'status',
        'started_by',
        'total',
        'processed',
        'updated',
        'failed',
        'skipped',
        'private',
        'last_id',
        'errors',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'errors' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];
}
