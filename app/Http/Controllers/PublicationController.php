<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use App\Models\Output;
use App\Models\Keyword;
use App\Models\OutputType;
use App\Models\BookChapter;
use Illuminate\Http\Request;
use App\Models\JournalArticle;
use App\Models\ConferencePaper;
use App\Models\ConferencePoster;
use App\Models\AuthorCitationName;
use Illuminate\Support\Facades\DB;
use App\Models\EventAdministration;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\OutputsFilterRequest;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $authorsIds = Author::whereHas('userInformation', function ($query) {
            $query->where('is_active', 1)
                ->where('type', '!=', 'administrative')
                ->where('is_isla', '=', Auth::user()->is_isla);
        })->pluck('id');

        $outputsType = OutputType::orderBy("name")->cursor();

        $outputsKeyWords = DB::table("keywords")->join("output_keywords", "keywords.id", "=", "output_keywords.keyword_id")->select("keyword", "id")->orderBy("keyword", 'ASC')->distinct()->cursor();

        $citationNames = AuthorCitationName::has("outputs")->cursor();
        $outputsInformation = Output::with(['polymorphic', 'authors' => function ($query) use ($authorsIds) {
            $query->whereIn('author_outputs.author_id', $authorsIds);
        }, 'type'])
            ->whereHas('authors', function ($query) use ($authorsIds) {
                $query->whereIn('author_outputs.author_id', $authorsIds);
            })
            ->orderBy('year', 'DESC')
            ->distinct()
            ->get();
        $releaseYears = Output::selectRaw('MIN(year) AS oldest_year, MAX(year) AS newest_year')->first();
        $oldestYear = $releaseYears->oldest_year;
        $newestYear = $releaseYears->newest_year;

        return view("outputs.index", compact("outputsInformation", "outputsType", "outputsKeyWords", "citationNames", "oldestYear", "newestYear"));
    }

    public function showAuthorOutputs($authorsId)
    {

        $author = Author::with([
            "output.polymorphic" => function ($query) {
                $query->orderBy("publication_year", "DESC");
            },
            "output" => function ($query) {
                $query->orderBy("year", "DESC");
            },
            "output.type"
        ])->findOrFail($authorsId);
        return view("authors.authorsInformation.output", compact(["author"]));
    }


    public function filter(OutputsFilterRequest $request)
    {
        $dataValidated = $request->validated();


        $filteredResults = Output::whereHasMorph(
            'polymorphic',
            '*',
            function (Builder $query, string $type) use ($request, $dataValidated) {
                if ($request->filled("status") && ($type === Book::class || $type === ConferencePaper::class || $type === JournalArticle::class || $type === BookChapter::class)) {
                    $query->whereIn("status", $dataValidated["status"]);
                }
            }
        )
            ->when($request->filled("title"), function ($query) use ($dataValidated) {
                return $query->where('title', 'like', '%' . $dataValidated["title"] . '%');
            })
            ->when($request->filled("category"), function ($query) use ($dataValidated) {
                return $query->whereIn('type_id', $dataValidated["category"]);
            })
            ->when($request->filled("keywords"), function ($query) use ($dataValidated) {
                return $query->whereHas("keywords", function ($query) use ($dataValidated) {
                    $query->whereIn("id", $dataValidated["keywords"]);
                });
            })
            ->when($request->filled("citationNames"), function ($query) use ($dataValidated) {
                return $query->whereHas("authors", function ($query) use ($dataValidated) {
                    $query->whereIn("citation_id", $dataValidated["citationNames"]);
                });
            })
            ->when($request->filled("startYear") && $request->filled("endYear"), function ($query) use ($dataValidated) {
                return $query->whereBetween("year", [$dataValidated["startYear"], $dataValidated["endYear"]]);
            })
            ->select("id", "title", "doi", "type_id", "model_id", "year", "output_type_class", "citation_string")
            ->with(["authors:id", "type:id,name", "polymorphic"]);

        $theOutputsWereFiltered = (count($filteredResults->getQuery()->wheres) != 0);

        if ($theOutputsWereFiltered) {
            $filteredResults = $filteredResults->get();
        } else {
            $filteredResults = Output::with(["authors", "type", "polymorphic"])->get();
        }

        return response()->json([compact("filteredResults")], 200);
    }
}
