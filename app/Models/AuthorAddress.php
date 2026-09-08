<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'adress',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'use_type',
    ];

    public function getAddressAttribute()
    {
        return $this->adress;
    }

    public function setAddressAttribute($value)
    {
        $this->attributes['adress'] = $value;
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
