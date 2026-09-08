<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAdministration extends Model
{
    use HasFactory;

    protected $fillable = [
        "activity_start_year",
        "activity_start_month",
        "activity_start_day",
        "activity_end_year",
        "activity_end_month",
        "activity_end_day",
        "event_description",
        "event_type",
        "administrative_role",
    ];

    public function service()
    {
        return $this->morphOne(Service::class, 'polymorphic', 'service_type_class', 'model_id');
    }
}
