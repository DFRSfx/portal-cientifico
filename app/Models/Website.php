<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    protected $fillable = [
        "output_id",
        "title",
        "description",
        "launch_date",
        "url",
    ];

    public function output()
    {
        return $this->morphOne(Output::class, "publication");
    }
}
