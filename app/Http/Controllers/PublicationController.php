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
        $outputsType = OutputType::orderBy("name")->cursor();

        $outputsKeyWords = DB::table("keywords")->join("output_keywords", "keywords.id", "=", "output_keywords.keyword_id")->select("keyword", "id")->orderBy("keyword", 'ASC')->distinct()->cursor();

        $citationNames = AuthorCitationName::has("outputs")->cursor();
        $outputsInformation = Output::with(['polymorphic', 'authors', 'type'])
            ->orderBy('year', 'DESC')
            ->distinct()
            ->get();
        $releaseYears = Output::selectRaw('MIN(year) AS oldest_year, MAX(year) AS newest_year')->first();
        $oldestYear = $releaseYears->oldest_year;
        $newestYear = $releaseYears->newest_year;

        return view("outputs.index", compact("outputsInformation", "outputsType", "outputsKeyWords", "citationNames", "oldestYear", "newestYear"));
    }

    public function show($id)
    {
        $output = Output::with([
            'polymorphic',
            'authors.userInformation',
            'type',
            'keywords',
            'citations',
            'projects'
        ])->findOrFail($id);

        // Track view count per session
        $viewedKey = 'viewed_output_' . $output->id;
        if (!session()->has($viewedKey)) {
            $output->incrementViews();
            session()->put($viewedKey, true);
        }

        // Find all ISLA authors associated with this work (including co-authors who synced the same output)
        $institutionAuthors = Author::whereHas('output', function ($q) use ($output) {
            $q->where('outputs.id', $output->id)
              ->orWhere('title', $output->title);
            if (!empty($output->doi)) {
                $q->orWhere('doi', $output->doi);
            }
        })
        ->with('userInformation')
        ->distinct()
        ->get();

        $relatedOutputs = Output::with(['type', 'authors'])
            ->where('id', '!=', $output->id)
            ->where(function ($q) use ($output) {
                if ($output->type_id) {
                    $q->where('type_id', $output->type_id);
                }
            })
            ->orderBy('year', 'DESC')
            ->limit(3)
            ->get();

        return view('outputs.show', compact('output', 'institutionAuthors', 'relatedOutputs'));
    }

    public function showAuthorOutputs(Request $request, $authorsId)
    {
        $typeName = trim((string) $request->query('type', ''));

        $author = Author::with([
            "output.polymorphic" => function ($query) {
                $query->orderBy("publication_year", "DESC");
            },
            "output" => function ($query) {
                $query->select('outputs.*')->distinct()->orderBy("year", "DESC");
            },
            "output.type"
        ])->findOrFail($authorsId);

        $outputs = null;
        if ($typeName !== '') {
            $outputs = $author->output()
                ->with(['polymorphic', 'type'])
                ->whereHas('type', function ($query) use ($typeName) {
                    $query->where('name', $typeName);
                })
                ->select('outputs.*')
                ->distinct()
                ->orderBy('year', 'DESC')
                ->get();
        }

        return view("authors.authorsInformation.output", compact(["author", "outputs", "typeName"]));
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

    /**
     * Fetch native Ciência Vitae coauthors for an output
     *
     * @param Output $output
     * @return \Illuminate\Http\JsonResponse
     */
    public function coauthors(Output $output): \Illuminate\Http\JsonResponse
    {
        $author = $output->authors()->with('userInformation')->first();
        $cienciaId = $author?->userInformation?->ciencia_vitae;
        $cvPubId = $output->ciencia_vitae_pub_id;

        if (!$cienciaId || !$cvPubId) {
            return response()->json([
                'error' => true,
                'message' => __('Identificador Ciência Vitae não disponível para esta publicação.')
            ], 404);
        }

        $cvController = new CienciaVitaeController();
        $coauthors = $cvController->getOutputCoauthors($cienciaId, $cvPubId);

        return response()->json([
            'error' => false,
            'coauthors' => $coauthors
        ]);
    }
}
