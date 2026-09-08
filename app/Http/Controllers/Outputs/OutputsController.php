<?php

namespace App\Http\Controllers\Outputs;

use App\Models\Author;
use App\Models\Output;
use App\Models\Keyword;
use App\Models\OutputType;
use Illuminate\Http\Request;
use App\Models\ConferencePaper;
use App\Models\ConferencePoster;
use Illuminate\Support\Facades\DB;
use App\Models\EventAdministration;
use App\Http\Controllers\Controller;
use App\Http\Requests\OutputsFilterRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\Builder;

class OutputsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $outputsType = DB::table("output_types")->orderBy("name")->get();

        $outputsKeyWords = DB::table("keywords")->join("output_keywords", "keywords.id", "=", "output_keywords.keyword_id")->select("keyword", "id")->orderBy("keyword", 'ASC')->distinct()->get();

        $citationNames = DB::table("author_citation_names")->get();

        $outputsInformation = Output::with(["polymorphic", "authors", "type"])->get();

        $oldestDate = $this->oldestDate(); //this will call  the variable grreting from the previous function

        return view("outputs.index", compact("outputsInformation", "outputsType", "outputsKeyWords", "citationNames", "oldestDate"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function showAuthorOutputs($authorsId)
    {
        $authors = Author::with(["output.polymorphic", "output.type"])->findOrFail($authorsId);

        return view("authors.authorsInformation.output", compact(["authors"]));
    }

    public function oldestDate()
    {
        $allPubs = Output::unionAllPubs();

        $oldestYear = DB::table(DB::raw("({$allPubs->toSql()}) AS s"))
            ->select('s.year')
            ->groupBy("s.year")
            ->mergeBindings($allPubs)
            ->orderBy('s.year', "asc")->value('s.year');

        return $oldestYear;
    }

    public function filter(OutputsFilterRequest $request)
    {
        $dataValidated = $request->validated();

        $filteredResults = Output::whereHasMorph(
            'polymorphic',
            '*',
            function (Builder $query, string $type) use ($request) {

                if ($request->input("startYear") && $request->input("endYear")) {
                    $column = ($type === ConferencePaper::class || $type === ConferencePoster::class) ? 'conference_year' : 'publication_year';

                    $query->whereBetween($column, [$request->input("startYear"), $request->input("endYear")]);
                }

                if ($request->input("status")) {
                    $query->whereIn("type", $request->input("status"));
                }
            }
        )->with(["authors", "type", "polymorphic"])
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
            });

        $theOutputsWereFiltered = (count($filteredResults->getQuery()->wheres) != 0);

        if ($theOutputsWereFiltered) {
            $filteredResults = $filteredResults->get();
        } else {
            $filteredResults = Output::with(["authors", "type", "polymorphic"])->get();
        }

        return response()->json([compact("filteredResults")], 200);
    }
}
