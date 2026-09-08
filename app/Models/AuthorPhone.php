<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'number',
        'phone',
        'phone_number',
        'type',
        'use_type',
    ];

    public function getPhoneAttribute()
    {
        return $this->number;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['number'] = $value;
    }

    public function getPhoneNumberAttribute()
    {
        return $this->number;
    }

    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['number'] = $value;
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
