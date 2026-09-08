<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Output;
use App\Models\Service;
use App\Models\OutputType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\AuthorSearchRequest;
use App\Http\Requests\StatisticsPublicationsRequest;
use App\Http\Requests\StatisticsServicesRequest;
use App\Models\ServiceType;

class StatisticsController extends Controller
{

    /*
    
    Global Statistics

    */
    public function index(Request $request)
    {
        if ($request->user()->type != "administrative") {
            abort(403);
        }

        $publicationsByYear = Output::select("year", "type_id", DB::raw("count(*) as count"))
            ->addSelect([
                "type" => OutputType::select("name")->whereColumn("type_id", "output_types.id"),
            ])
            ->where("year", "!=", null)
            ->groupBy("year", "type_id")
            ->orderBy("year", "asc")->get();

        $numberOfPublications = Output::count();

        $average = round($publicationsByYear->avg('count'));
        $releaseYears = Output::selectRaw('MIN(year) AS oldest_year, MAX(year) AS newest_year')->first();
        $oldestYear = $releaseYears->oldest_year;
        $newestYear = $releaseYears->newest_year;

        // User counters
        $activeUsersCount = User::where('is_active', 1)->count();
        $pendingUsersCount = User::where('is_active', 0)->count();
        $pendingVerificationCount = User::whereNull('email_verified_at')->count();

        return view("pages.statistics", compact(
            'publicationsByYear',
            'average',
            'numberOfPublications',
            'oldestYear',
            'newestYear',
            'activeUsersCount',
            'pendingUsersCount',
            'pendingVerificationCount'
        ));
    }

    public function getPublicationsStats(StatisticsPublicationsRequest $request)
    {
        $dataValidated = $request->validated();

        if (!isset($dataValidated["authorId"]) && $request->user()->type != "administrative") {
            return response()->json(["message" => "Access not Authorized"], 403);
        }

        $outputsByYear = Output::when($request->filled("startYear") && $request->filled("endYear"), function ($query) use ($dataValidated) {
            return $query->whereBetween("year", [$dataValidated["startYear"], $dataValidated["endYear"]]);
        }, function ($query) {
            return $query->where("year", "!=", null);
        })
            ->when($request->filled("authorId"), function ($query) use ($dataValidated) {
                return $query->whereHas("authors", function ($q) use ($dataValidated) {
                    $q->where("author_id", "=", $dataValidated["authorId"]);
                });
            })
            ->when(true, function ($query) use ($dataValidated) {

                if ($dataValidated["statsType"] == "type") {
                    return $query->select("year", "type_id", DB::raw("count(*) as count"))
                        ->addSelect([
                            "type" => OutputType::select("name")->whereColumn("type_id", "output_types.id"),
                        ])->groupBy("year", "type_id");
                } else {
                    return $query->select("year", DB::raw("count(*) as count"))
                        ->groupBy("year");
                }
            })
            ->orderBy("year", "asc")->get();


        $average = round($outputsByYear->avg('count'));

        return response()->json(["data" => $outputsByYear, "average" => $average]);
    }

    public function getEventsStats(StatisticsServicesRequest $request): JsonResponse
    {
        $dataValidated = $request->validated();

        if (!isset($dataValidated["authorId"]) && $request->user()->type != "administrative") {
            return response()->json(["message" => "Access not Authorized"], 403);
        }

        $event = Service::when($request->filled("startYear") && $request->filled("endYear"), function ($query) use ($dataValidated) {
            return $query->whereBetween("end_year", [$dataValidated["startYear"], $dataValidated["endYear"]]);
        }, function ($query) {
            return $query->where("end_year", "!=", null);
        })
            ->when($request->filled("authorId"), function ($query) use ($dataValidated) {
                return $query->where("author_id", "=", $dataValidated["authorId"]);
            })
            ->when(true, function ($query) use ($dataValidated) {

                switch ($dataValidated["statsType"]) {
                    case "global":
                        return $query->select("end_year as year", DB::raw("count(*) as count"))
                            ->groupBy("year");
                        break;
                    case "type":
                        return $query->select("end_year as year", "type_id", DB::raw("count(*) as count"))
                            ->addSelect([
                                "type" => ServiceType::select("name")->whereColumn("type_id", "service_types.id"),
                            ])->groupBy("year", "type_id");
                        break;
                }
            })
            ->orderBy("year", "asc")->get();

        return response()->json(["data" => $event]);
    }
}
