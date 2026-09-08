<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jury extends Model
{
    //
    protected $fillable = [
        'theme',
        'examination_subject',
        'year',
        "start_date",
        "end_date",

    ];

    public function service()
    {
        return $this->morphOne(Service::class, 'polymorphic', 'service_type_class', 'model_id');
    }
}
