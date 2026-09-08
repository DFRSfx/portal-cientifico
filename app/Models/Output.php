<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use PhpParser\ErrorHandler\Collecting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Output extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "doi",
        "quartile",
        "type_id",
        "model_id",
        "citation_string",
        "output_type_class",
        "year",
        "ciencia_vitae_pub_id"
    ];

    public function polymorphic(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'output_type_class', 'model_id');
    }

    public function keywords()
    {
        return $this->belongsToMany(Keyword::class, "output_keywords", "output_id", "keyword_id");
    }

    public function type()
    {
        return $this->belongsTo(OutputType::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class, "author_outputs")->withPivot(
            "citation_id"
        )->withTimestamps();
    }

    public function citations()
    {
        return $this->belongsToMany(AuthorCitationName::class, "author_outputs", "output_id", "citation_id")->withPivot(
            "author_id"
        )->withTimestamps();
    }

    /**
     * Delete morpho related models
     */
    function delete()
    {
        $this->polymorphic()->delete(); // DELETE * FROM files WHERE user_id = ? query

        parent::delete();
    }

    /*

    Union all polymorfic relations

    */
    public static function  unionAllPubs($authorId = null, $startYear = null, $endYear = null)
    {
        $numberOfRelationsDone = 0;

        $tables = [
            "conference_papers" => [
                "className" => "ConferencePaper"
            ],
            "conference_posters" => [
                "className" => "ConferencePoster"
            ],
            "books" => [
                "className" => "Book"
            ],
            "book_chapters" => [
                "className" => "BookChapter"
            ],
            "magazine_articles" => [
                "className" => "MagazineArticles"
            ],
            "journal_articles" => [
                "className" => "JournalArticle"
            ],
            "thesis_dissertations" => [
                "className" => "ThesisDissertation"
            ],
            "newspaper_articles" => [
                "className" => "NewspaperArticle"
            ],
        ];

        foreach ($tables as $tableName => $tableProprieties) {
            $yearColumName = "publication_year";

            if ($tableName == "conference_papers" || $tableName == "conference_posters") {
                $yearColumName = "conference_year";
            }

            $year = $tableName . "." . $yearColumName . " as year";

            $selectColumns = "outputs.output_type_class as class, outputs.citation_string , output_types.name as type, outputs.title as title, outputs.doi," . $year;

            $className = "App\Models\\" . $tableProprieties["className"];

            $query = DB::table('outputs')->selectRaw($selectColumns)
                ->Join('output_types', 'outputs.type_id', '=', 'output_types.id')
                ->Join($tableName, "outputs.model_id", "=", $tableName . ".id")
                ->Join("author_outputs", "outputs.id", "=", "author_outputs.output_id")
                ->Join("authors", "authors.id", "=", "author_outputs.author_id")
                ->where("outputs.output_type_class", $className)
                ->where($yearColumName, "!=", "null");

            if ($startYear != null && $endYear != null) {
                $query->whereBetween($yearColumName, [$startYear, $endYear]);
            }

            if ($authorId != null) {
                $query->where("author_outputs.author_id", "=", $authorId);
            }

            if ($numberOfRelationsDone > 0) {
                $allPubs->unionAll($query);
            } else {
                $allPubs = $query;
            }

            $numberOfRelationsDone++;
        }

        return $allPubs;
    }
}
