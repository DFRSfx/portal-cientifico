<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MagazineArticles extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_title',
        'magazine',
        'volume',
        'issue',
        'start_page',
        'end_page',
        'date',
        'country',
        'city',
        'role',
        'url'

    ];

    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }
}
