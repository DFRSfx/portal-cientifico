<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommiteeMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        "committee_name",
        "membership_type",
    ];

    public function service()
    {
        return $this->morphOne(Service::class, 'polymorphic', 'service_type_class', 'model_id');
    }
}
