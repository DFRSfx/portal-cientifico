<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    use HasFactory;

    protected $fillable = [
        "supervisor_name",
        "supervisor_role",
        "ciencia_vitae",
        "degree_id",
    ];
}
