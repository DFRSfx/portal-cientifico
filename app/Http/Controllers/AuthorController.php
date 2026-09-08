<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Output;
use App\Models\Language;
use App\Models\OutputType;
use App\Models\CvUpdateRun;
use App\Jobs\UpdateAllAuthorsJob;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\Response;
use App\Models\EventParticipation;
use Illuminate\Support\Facades\DB;
use App\Models\EventAdministration;
use Illuminate\Support\Facades\Auth;
use App\Models\DomainActivitiesTopic;
use App\Models\Entity;
use App\Http\Requests\AuthorSearchRequest;
use App\Http\Controllers\CienciaVitaeController;

class AuthorController extends Controller
{

    /**
     * @var App\Http\Controllers\CienciaVitaeController
     */
    private $cienciaVitaeApi;

    public function __construct()
    {
        $this->cienciaVitaeApi = new CienciaVitaeController();
    } //__construct

    /**
     * Display a list of all the teachers
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): \Illuminate\Contracts\View\View
    {
        // Professores
        if (auth()->user()->type == "administrative") {
            $authors = Author::with(["userInformation:id,name,ciencia_vitae,email,type"])->whereHas("userInformation", function ($query) {
                $query->where("is_active", "=", "1")->where("type", "!=", "administrative");
            })->cursor();
        } else {
            $authors = Author::with(["userInformation:id,name,ciencia_vitae,email,type"])->whereHas("userInformation", function ($query) {
                $query->where("is_active", "=", "1")->where("type", "!=", "administrative")->where("is_isla", '=', Auth::user()->is_isla);
            })->cursor();
        }

        // Atividades
        $topics = DomainActivitiesTopic::cursor();

        $languages = Language::cursor();

        $outputsKeyWords = DB::table("keywords")->select("keyword", "id")->orderBy("keyword")->distinct()->cursor();

        $entitiesList = Entity::orderByRaw("case when name = 'OTHER' then 1 else 0 end")
            ->orderBy('name')
            ->get();

        return View('authors.index', compact(["authors", "topics", "languages", "outputsKeyWords", "entitiesList"]));
    }

public function getAllAuthorIds()
{
    $ids = Author::whereHas('userInformation', function($q) {
        $q->whereNotNull('ciencia_vitae');
    })->pluck('id')->toArray();

    return response()->json(['ids' => $ids]);
}


public function updateAllAuthors(Request $request)
{
    set_time_limit(300);
    ini_set('memory_limit', '1024M');

    $batchSize   = (int) $request->get('batchSize', 3);
    $lastId      = (int) $request->get('lastId', 0);

    $query = Author::where('id', '>', $lastId)
        ->orderBy('id')
        ->with('userInformation');

    $authors = $query->limit($batchSize)->get();

    if ($authors->isEmpty()) {
        return response()->json([
            'done'    => true,
            'lastId'  => $lastId,
            'message' => 'Atualizacao concluida para todos os autores.'
        ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    $updated = 0;
    $failed  = 0;
    $skipped = 0;
    $private = 0;
    $currentLastId = $lastId;
    $errors = [];

    foreach ($authors as $author) {
        $currentLastId = $author->id;

        try {
            if (!$author->userInformation || !$author->userInformation->ciencia_vitae) {
                \Log::info('Skipping author without Ciencia Vitae', ['author_id' => $author->id]);
                $skipped++;
                continue;
            }

            \Log::info('Processing author', [
                'author_id' => $author->id,
                'ciencia_vitae' => $author->userInformation->ciencia_vitae
            ]);

            $authorCurriculumIsPublic = $this->cienciaVitaeApi->authorCurriculumIsPub($author->userInformation->ciencia_vitae);

            if ($authorCurriculumIsPublic === 1) {
                sleep(1);

                $endPoint = "curriculum/" . urlencode($author->userInformation->ciencia_vitae);
                $responseArray = $this->cienciaVitaeApi->cienciaVitaeRequest($endPoint);

                if (gettype($responseArray) != "array") {
                    \Log::error('Invalid response from Ciencia Vitae', [
                        'author_id' => $author->id,
                        'response_type' => gettype($responseArray)
                    ]);
                    $failed++;
                    $errors[] = "Author {$author->id}: Invalid API response";
                    continue;
                }

                $response = $this->cienciaVitaeApi->updateAuthorInformation($responseArray, $author);

                if ($response["error"]) {
                    \Log::error('Failed to update author', [
                        'author_id' => $author->id,
                        'message' => $response["message"]
                    ]);
                    $failed++;
                    $errors[] = "Author {$author->id}: " . $response["message"];
                    continue;
                }

                \Log::info('Successfully updated author', ['author_id' => $author->id]);
                $updated++;

            } else if ($authorCurriculumIsPublic === 0) {
                \Log::info('Author curriculum is private', ['author_id' => $author->id]);

                $author->update([
                    "profile_is_public" => 0,
                    "profile_image_is_public" => 0,
                    "profile_updated_date" => null,
                    "orcid" => "",
                    'id_google_scholar' => "",
                    'id_researcher' => "",
                    'id_scopus_author' => "",
                    'researchgate_profile' => "",
                    'id_lattes' => "",
                    "resume" => ""
                ]);

                $informationRelationsToRemove = [
                    "citationName",
                    "email",
                    "activity",
                    "employments",
                    "degrees",
                    "service",
                    "output",
                    "languages",
                    "project"
                ];

                foreach ($informationRelationsToRemove as $relation) {
                    $author->{$relation}()->delete();
                }

                Output::doesntHave("authors")->delete();

                $this->cienciaVitaeApi->removeAllPolymorphicRelations();

                $private++;

            } else {
                \Log::error('Error checking curriculum status', ['author_id' => $author->id]);
                $failed++;
                $errors[] = "Author {$author->id}: Error checking curriculum status";
            }

        } catch (\Throwable $e) {
            \Log::error('Exception updating author', [
                'author_id' => $author->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $failed++;
            $errors[] = "Author {$author->id}: " . $e->getMessage();
        }

        gc_collect_cycles();
    }

    return response()->json([
        'done'    => false,
        'lastId'  => $currentLastId,
        'updated' => $updated,
        'failed'  => $failed,
        'skipped' => $skipped,
        'private' => $private,
        'errors'  => array_slice($errors, 0, 10),
        'message' => "Batch concluido. {$updated} atualizados, {$failed} falharam, {$skipped} ignorados, {$private} privados."
    ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
}

    /**
     * Starts a background CV update run (admin only)
     */
    public function startUpdateAllAuthorsJob(Request $request): \Illuminate\Http\JsonResponse
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $batchSize = (int) $request->input('batchSize', 10);
        if ($batchSize < 1) {
            $batchSize = 10;
        }

        $run = CvUpdateRun::create([
            'status' => 'queued',
            'started_by' => auth()->id(),
        ]);

        UpdateAllAuthorsJob::dispatch($run->id, $batchSize);

        return response()->json([
            'runId' => $run->id,
            'message' => 'Atualizacao em background iniciada.'
        ]);
    }

    /**
     * Returns status for a background CV update run (admin only)
     */
    public function getUpdateAllAuthorsJobStatus(int $runId): \Illuminate\Http\JsonResponse
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $run = CvUpdateRun::find($runId);

        if (!$run) {
            return response()->json(['message' => 'Run not found.'], 404);
        }

        return response()->json([
            'status' => $run->status,
            'total' => $run->total,
            'processed' => $run->processed,
            'updated' => $run->updated,
            'failed' => $run->failed,
            'skipped' => $run->skipped,
            'private' => $run->private,
            'lastId' => $run->last_id,
            'errors' => $run->errors,
            'startedAt' => $run->started_at,
            'finishedAt' => $run->finished_at,
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function show($id): \Illuminate\Contracts\View\View
    {
	$authors =Author::all();
        $author = Author::with(
            [
                "citations",
                "languages",
                "activity",
                "project",
                "emails",
                "phones",
                "addresses",
                "websites",
                "degrees" => function ($query) {
                    $query->orderBy("end_date_year", "desc");
                },
                "userInformation:id,name,ciencia_vitae,email,type"
            ]
        )->withCount("output")->findOrFail($id);
        
        return view('authors.authorsInformation.basicInfo', compact("author", "authors"));
    }
    /**
     * Display author outputs, optionally filtered by type
     * 
     * @param Request $request
     * @param int $authorsId
     * @return \Illuminate\Contracts\View\View
     */
    public function outputs(Request $request, $authorsId)
    {
        $author = Author::with(['output.type', 'userInformation'])->findOrFail($authorsId);
        
        // Inicia a query base
        $query = $author->output();

        // Aplica filtro por tipo se fornecido
        if ($request->has('type')) {
            $rawType = (string) $request->input('type');
            $typeId = (int) $rawType;

            if ($typeId > 0) {
                $query->where('type_id', $typeId);
            } else {
                $typeIdByName = OutputType::where('name', $rawType)->value('id');

                if ($typeIdByName) {
                    $query->where('type_id', $typeIdByName);
                }
            }
        }

        // Carrega os relacionamentos e ordena
        $outputs = $query->with('type')
                        ->orderByDesc('year')
                        ->get();

        // Define as cores para os tipos (ajuste conforme necessário)
        $colors = ['badge-primary', 'badge-success', 'badge-info', 'badge-warning', 'badge-danger'];

        return view('authors.authorsInformation.output', compact('author', 'outputs', 'colors'));
    }

    /**
     * Updates the user curriculum using ciencia vitae
     * @return response
     */
    public function updateOneAuthorInfo(): \Illuminate\Http\JsonResponse
    {
        // Validate if the authors exists
        $author =  auth()->user()->authorInformation;

        $authorCurriculumIsPublic = $this->cienciaVitaeApi->authorCurriculumIsPub(auth()->user()->ciencia_vitae);
        if ($authorCurriculumIsPublic === 1) {
            //waits one second to respect the interval os time beetwen requests(recomended by ciencia vitae)
            sleep(1);
            // $informationRelationsToRemove = [
            //     "citationName",
            //     "email",
            //     "activity",
            //     "employments",
            //     "degrees",
            //     "service",
            //     "output",
            //     "languages",
            //     "project"
            // ];

            // //removes all the author information, if one profile that was public is now private the application should remove all the user data stored
            // for ($i = 0; $i < count($informationRelationsToRemove); $i++) {
            //     $author->{$informationRelationsToRemove[$i]}()->delete();
            // }

            // Output::doesntHave("authors")->delete();

            // $this->cienciaVitaeApi->removeAllPolymorphicRelations();

            $endPoint = "curriculum/" . urlencode(auth()->user()->ciencia_vitae);
            $responseArray = $this->cienciaVitaeApi->cienciaVitaeRequest($endPoint);
            // If the response is not one array the request failed
            if (gettype($responseArray) != "array") {
                return response()->json(["message" => "The request failed!"], $responseArray);
            }

            $response = $this->cienciaVitaeApi->updateAuthorInformation($responseArray, $author);
            $code = ($response["error"]) ? 500 : 200;

            $message = $response["message"];
        } else if ($authorCurriculumIsPublic === 0) {

            //identifies that the profile is private and has no image associated
            $author->update([
                "profile_is_public" => 0,
                "profile_image_is_public" => 0,
                "profile_updated_date" => null,
                "orcid" => "",
                'id_google_scholar' => "",
                'id_researcher' => "",
                'id_scopus_author' => "",
                "resume" => ""
            ]);

            $informationRelationsToRemove = [
                "citationName",
                "email",
                "activity",
                "employments",
                "degrees",
                "service",
                "output",
                "languages",
                "project"
            ];

            //removes all the author information, if one profile that was public is now private the application should remove all the user data stored
            for ($i = 0; $i < count($informationRelationsToRemove); $i++) {
                $author->{$informationRelationsToRemove[$i]}()->delete();
            }

            Output::doesntHave("authors")->delete();

            $this->cienciaVitaeApi->removeAllPolymorphicRelations();

            $code = 400;

            $message = "The Curriculum is not Published!";
        } else {
            $code = 500;

            $message = "Error!";
        }

        return response()->json(["message" => $message], $code);
    }

    /**
     * Updates a specific author curriculum (admin only)
     * @param int $authorId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAuthorById(int $authorId): \Illuminate\Http\JsonResponse
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $author = Author::with("userInformation")->find($authorId);

        if (!$author || !$author->userInformation || !$author->userInformation->ciencia_vitae) {
            return response()->json(["message" => "Autor sem Ciencia Vitae associado."], 400);
        }

        $authorCurriculumIsPublic = $this->cienciaVitaeApi->authorCurriculumIsPub($author->userInformation->ciencia_vitae);

        if ($authorCurriculumIsPublic === 1) {
            sleep(1);

            $endPoint = "curriculum/" . urlencode($author->userInformation->ciencia_vitae);
            $responseArray = $this->cienciaVitaeApi->cienciaVitaeRequest($endPoint);

            if (gettype($responseArray) != "array") {
                return response()->json(["message" => "The request failed!"], 500);
            }

            $response = $this->cienciaVitaeApi->updateAuthorInformation($responseArray, $author);
            $code = ($response["error"]) ? 500 : 200;

            return response()->json(["message" => $response["message"]], $code);
        }

        if ($authorCurriculumIsPublic === 0) {
            $author->update([
                "profile_is_public" => 0,
                "profile_image_is_public" => 0,
                "profile_updated_date" => null,
                "orcid" => "",
                'id_google_scholar' => "",
                'id_researcher' => "",
                'id_scopus_author' => "",
                "resume" => ""
            ]);

            return response()->json(["message" => "The Curriculum is not Published!"], 400);
        }

        return response()->json(["message" => "Error!"], 500);
    }

    /**
     * Update self-declared metrics for an author
     */
    public function updateMetrics(Request $request, int $authorId): \Illuminate\Http\RedirectResponse
    {
        $author = Author::with('userInformation')->findOrFail($authorId);

        $user = auth()->user();
        $canEdit = $user && ($user->type === 'administrative'
            || ($user->authorInformation && $user->authorInformation->id === $author->id));

        abort_if(!$canEdit, 403);

        $data = $request->validate([
            'h_index' => ['nullable', 'integer', 'min:0', 'max:200'],
            'h_index_source' => ['nullable', 'string', 'max:100'],
            'h_index_reported_at' => ['nullable', 'date'],
        ]);

        if ($data['h_index'] === null) {
            $author->h_index = null;
            $author->h_index_source = null;
            $author->h_index_reported_at = null;
            $author->h_index_is_self_declared = null;
        } else {
            $author->h_index = $data['h_index'];
            $author->h_index_source = $data['h_index_source'] ?? null;
            $author->h_index_reported_at = $data['h_index_reported_at'] ?? null;
            $author->h_index_is_self_declared = true;
        }

        $author->save();

        return redirect()
            ->back()
            ->with('status', __('Indicadores atualizados.'));
    }

    /**
     * Update quartile (self-declared) for a publication
     */
    public function updateOutputQuartile(Request $request, int $authorId, int $outputId): \Illuminate\Http\RedirectResponse
    {
        $author = Author::with('userInformation')->findOrFail($authorId);

        $user = auth()->user();
        $canEdit = $user && ($user->type === 'administrative'
            || ($user->authorInformation && $user->authorInformation->id === $author->id));

        abort_if(!$canEdit, 403);

        $data = $request->validate([
            'quartile' => ['nullable', 'in:Q1,Q2,Q3,Q4'],
        ]);

        $output = $author->output()->where('outputs.id', $outputId)->firstOrFail();
        $output->quartile = $data['quartile'] ?? null;
        $output->save();

        return redirect()->back();
    }

    public function projects($authorsId)
    {
        $author = Author::with(['project'])->findOrFail($authorsId);
        return view('authors.authorsInformation.projects', compact('author'));
    }

    /**
     * filters the authors
     * @param AuthorSearchRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(AuthorSearchRequest $request): \Illuminate\Http\JsonResponse
    {
        $dataValidated = $request->validated();

        $authors = Author::query()->with("userInformation:id,name,ciencia_vitae,email,type");

        $authors = $authors->when($request->filled("name"), function ($query) use ($dataValidated) {

            return $query->whereHas("userInformation", function ($q) use ($dataValidated) {
                $q->where('name', 'like', '%' . $dataValidated["name"] . '%');
            });
        })
            ->when($request->filled("entities"), function ($query) use ($dataValidated) {
                $entities = array_values(array_filter($dataValidated["entities"]));

                return $query->whereHas("userInformation", function ($q) use ($entities) {
                    $q->where(function ($sub) use ($entities) {
                        $sub->whereIn("entidade", $entities);

                        foreach ($entities as $entity) {
                            $sub->orWhere("entidade", "like", "%" . $entity . "%")
                                ->orWhereJsonContains("entities", $entity);
                        }
                    });
                });
            })
            ->when($request->filled("domainActivity"), function ($query) use ($dataValidated) {

                return $query->whereHas("activity", function ($q) use ($dataValidated) {
                    $q->whereIn("topic_id", $dataValidated["domainActivity"]);
                });
            })->when($request->filled("language"), function ($query) use ($dataValidated) {
                return $query->whereHas("languages", function ($q) use ($dataValidated) {
                    $q->whereIn("language_id", $dataValidated["language"]);
                });
            })->when($request->filled("keywords"), function ($query) use ($dataValidated) {

                return $query->whereHas("output.keywords", function ($q) use ($dataValidated) {
                    $q->whereIn("keyword_id", $dataValidated["keywords"]);
                });
            })->whereHas("userInformation", function ($query) {
                $query->where("is_active", "=", "1")->where("type", "!=", "administrative");
            });

        $authors = $authors->get();

        return response()->json(['message' => 'success', 'authors' => json_decode($authors)]);
    }

    /**
     * Gets all the author employments
     * @param int $id
     * @return
     */
    public function getAuthorEmployments($id): \Illuminate\Contracts\View\View
    {
        $author = Author::with(["employments" => function ($query) {
            $query->orderBy("start_date_year", "desc");
        }, "userInformation:id,name,ciencia_vitae,email,type"])->withCount("output")->findOrFail($id);

        return view("authors.authorsInformation.employments", compact("author"));
    }
    public function getAuthorActivities($id)
    {
        $author = Author::with([
            "service.polymorphic",
            "service" => function ($query) {
                $query->orderBy("start_year", "DESC");
            },
            "service.type"
        ])->findOrFail($id);

        return view("authors.authorsInformation.activities", compact(["author"]));
    }

    public function getAuthorStats($id)
    {
        $author = Author::findOrFail($id);

        $author->loadCount("output");

        // Gets the global stats

        $outputsByYear = Output::select("year", "type_id", DB::raw("count(*) as count"))
            ->addSelect([
                "type" => OutputType::select("name")->whereColumn("type_id", "output_types.id"),
            ])
            ->whereHas("authors", function ($q) use ($author) {
                $q->where("author_id", "=", $author->id);
            })
            ->where("year", "!=", null)
            ->groupBy("year", "type_id")
            ->orderBy("year", "asc")->get();

        $average = round($outputsByYear->avg('count'));

        return view("authors.authorsInformation.analytics", compact("outputsByYear", "author", "average"));
    }
}
