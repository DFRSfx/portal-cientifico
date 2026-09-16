<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\AuthorCitationName;
use App\Models\Output;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class AdminController extends Controller
{
    public function allPublications(Request $request)
    {
        abort_if(auth()->user()->type !== 'administrative', 403);

        $query = Output::with(['polymorphic', 'authors.userInformation', 'type', 'keywords']);

        // Quick filter for quality issues
        $filter = $request->get('filter');
        if ($filter === 'missing_doi') {
            $query->where(function ($q) {
                $q->whereNull('doi')->orWhere('doi', '');
            });
        } elseif ($filter === 'missing_year') {
            $query->where(function ($q) {
                $q->whereNull('year')->orWhere('year', 0);
            });
        } elseif ($filter === 'missing_type') {
            $query->whereNull('type_id');
        } elseif ($filter === 'missing_quartile') {
            $query->where(function ($q) {
                $q->whereNull('quartile')->orWhere('quartile', '');
            });
        } elseif ($filter === 'has_quartile') {
            $query->whereNotNull('quartile')->where('quartile', '!=', '');
        }

        $outputs = $query->orderBy('year', 'desc')->get();

        // Metrics for Quality System
        $totalOutputs = Output::count();
        $missingDoiCount = Output::whereNull('doi')->orWhere('doi', '')->count();
        $missingYearCount = Output::whereNull('year')->orWhere('year', 0)->count();
        $missingTypeCount = Output::whereNull('type_id')->count();
        $withQuartileCount = Output::whereNotNull('quartile')->where('quartile', '!=', '')->count();
        $missingQuartileCount = $totalOutputs - $withQuartileCount;

        return view('admin.quality', [
            'outputs' => $outputs,
            'totalOutputs' => $totalOutputs,
            'missingDoiCount' => $missingDoiCount,
            'missingYearCount' => $missingYearCount,
            'missingTypeCount' => $missingTypeCount,
            'withQuartileCount' => $withQuartileCount,
            'missingQuartileCount' => $missingQuartileCount,
            'activeFilter' => $filter,
        ]);
    }

    public function updateQuality(Request $request, $outputId)
    {
        abort_if(auth()->user()->type !== 'administrative', 403);

        $validated = $request->validate([
            'quartile' => 'nullable|string|in:Q1,Q2,Q3,Q4,N/A',
            'doi' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        $output = Output::findOrFail($outputId);
        $output->update([
            'quartile' => $validated['quartile'] ?? $output->quartile,
            'doi' => $validated['doi'] ?? $output->doi,
            'year' => $validated['year'] ?? $output->year,
        ]);

        return redirect()->back()->with('success', __('Dados de qualidade atualizados com sucesso para a publicação.'));
    }

    /**
     * Search researchers across institutions using Ciência Vitae API (v1.1)
     */
    public function institutionSearch(Request $request)
    {
        abort_if(auth()->user()->type !== 'administrative', 403);

        $institutionQuery = $request->get('institution', 'ISLA');
        $page = max(1, (int) $request->get('page', 1));
        $pageSize = 25;
        $results = null;
        $error = null;

        if ($request->filled('institution')) {
            try {
                $cvController = new CienciaVitaeController();
                $results = $cvController->searchPersonsByInstitution($institutionQuery, $page, $pageSize);
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }
        }

        $existingCienciaIds = \App\Models\UserInformation::whereNotNull('ciencia_vitae')
            ->pluck('ciencia_vitae')
            ->map(fn($id) => strtoupper(trim((string)$id)))
            ->filter()
            ->values()
            ->all();

        return view('admin.institution-search', [
            'institutionQuery' => $institutionQuery,
            'page' => $page,
            'results' => $results,
            'error' => $error,
            'existingCienciaIds' => $existingCienciaIds,
        ]);
    }
}
