<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'creation_date',
        'url',
    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
