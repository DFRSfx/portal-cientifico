<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookReview extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'review_title',
        'published_in',
        'review_volume',
        'review_issue',
        'start_page',
        'end_page',
        'refereed',
        'publication_status',
        'review_publication',
        'date_of_review_publication',
        'review_publisher',
        'url',
        'book_title',
        'book_volume',
        'book_edition',
        'book_refereed',
        'book_publication_year',
        'book_publication_location',
    ];
    
    public function output()
    {
        return $this->morphOne(Output::class, 'polymorphic', "output_type_class", "model_id");
    }

}
