<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supervision extends Model
{
    protected $fillable = [
        "start_date",
        "end_date",
        "thesis_title",
        "supervisory_title",
    ];

    public function service()
    {
        return $this->morphOne(Service::class, 'polymorphic', 'service_type_class', 'model_id');
    }
}
