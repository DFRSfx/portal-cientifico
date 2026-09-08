<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        "start_year",
        "start_month",
        "start_day",
        "end_year",
        "end_month",
        "end_day",
        "model_id",
        "service_type_class",
        "type_id",
        "author_id",
    ];

    public function polymorphic()
    {
        return $this->morphTo(__FUNCTION__, 'service_type_class', 'model_id');
    }

    public function type()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, "service_keywords" , "service_id", "keyword_id");
    }


    /**
     * Delete morpho related models
     */
    function delete()
    {
        $this->polymorphic()->delete(); // DELETE * FROM files WHERE user_id = ? query

        parent::delete();
    }
}
