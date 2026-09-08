<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutputType extends Model
{
    use HasFactory;

    protected $fillable = [
        "name"
    ];

    public function output()
    {
        return $this->hasMany(Output::class,"type_id");
    }

}
