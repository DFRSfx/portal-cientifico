<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class authorOutputs extends Model
{
    use HasFactory;

    protected $fillable = [
        "author_id",
        "output_id",
        "citation_id",
    ];
}
