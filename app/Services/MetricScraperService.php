<?php

namespace App\Services;

use App\Models\Author;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetricScraperService
{
    /**
     * Sincroniza as métricas (H-index, citações) para o Scopus e o Google Scholar separadamente
     */
    public function syncAuthorMetrics(Author $author): array
    {
        $results = [
            'google_scholar' => null,
            'scopus' => null,
            'updated' => false,
        ];

        $hasChanges = false;

        // 1. Google Scholar
        if (!empty($author->id_google_scholar)) {
            $scholarData = $this->fetchGoogleScholarMetrics($author->id_google_scholar);
            $results['google_scholar'] = $scholarData;

            if ($scholarData && isset($scholarData['h_index'])) {
                $author->h_index_scholar = $scholarData['h_index'];
                $author->citations_scholar = $scholarData['citations'] ?? null;
                $hasChanges = true;
            }
        }

        // 2. Scopus
        if (!empty($author->id_scopus_author)) {
            $scopusData = $this->fetchScopusMetrics($author->id_scopus_author);
            $results['scopus'] = $scopusData;

            if ($scopusData && (isset($scopusData['h_index']) || isset($scopusData['citations']))) {
                if (isset($scopusData['h_index'])) {
                    $author->h_index_scopus = $scopusData['h_index'];
                }
                if (isset($scopusData['citations'])) {
                    $author->citations_scopus = $scopusData['citations'];
                }
                $hasChanges = true;
            }
        }

        if ($hasChanges) {
            // H-index principal (preferindo Scholar ou Scopus se existente)
            $author->h_index = $author->h_index_scholar ?? $author->h_index_scopus;
            $author->h_index_reported_at = now();
            $author->h_index_is_self_declared = false;
            $author->save();
            $results['updated'] = true;
        }

        return $results;
    }

    /**
     * Extrai métricas do perfil público do Google Scholar
     */
    public function fetchGoogleScholarMetrics(string $scholarId): ?array
    {
        try {
            $url = "https://scholar.google.com/citations?user=" . urlencode($scholarId) . "&hl=en";
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->get($url);

            if (!$response->successful()) {
                Log::warning("Google Scholar scraper returned status " . $response->status() . " for ID {$scholarId}");
                return null;
            }

            $html = $response->body();
            $metrics = [
                'h_index' => null,
                'citations' => null,
                'i10_index' => null,
            ];

            if (preg_match('/<table id="gsc_rsb_st">(.*?)<\/table>/is', $html, $tableMatch)) {
                $tableHtml = $tableMatch[1];

                // Extrair h-index
                if (preg_match('/<tr[^>]*>.*?h-index.*?<td class="gsc_rsb_std">(\d+)<\/td>/is', $tableHtml, $m)) {
                    $metrics['h_index'] = (int) $m[1];
                }

                // Extrair citações
                if (preg_match('/<tr[^>]*>.*?Citations.*?<td class="gsc_rsb_std">(\d+)<\/td>/is', $tableHtml, $m)) {
                    $metrics['citations'] = (int) $m[1];
                }

                // Extrair i10-index
                if (preg_match('/<tr[^>]*>.*?i10-index.*?<td class="gsc_rsb_std">(\d+)<\/td>/is', $tableHtml, $m)) {
                    $metrics['i10_index'] = (int) $m[1];
                }
            }

            return $metrics['h_index'] !== null ? $metrics : null;
        } catch (\Throwable $e) {
            Log::error("Error scraping Google Scholar for {$scholarId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extrai métricas do Scopus via API oficial Elsevier ou Scraping da página HTML do autor
     */
    public function fetchScopusMetrics(string $scopusId): ?array
    {
        // 1. Tentar API Elsevier se tiver chave configurada
        $apiKey = config('services.elsevier.api_key', env('ELSEVIER_API_KEY'));
        if (!empty($apiKey)) {
            try {
                $url = "https://api.elsevier.com/content/author?author_id=" . urlencode($scopusId);
                $response = Http::timeout(10)
                    ->withHeaders([
                        'X-ELS-APIKey' => $apiKey,
                        'Accept' => 'application/json',
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    $hIndex = data_get($data, 'author-retrieval-response.0.h-index')
                        ?? data_get($data, 'author-retrieval-response.coredata.h-index');
                    $citationCount = data_get($data, 'author-retrieval-response.0.coredata.citation-count')
                        ?? data_get($data, 'author-retrieval-response.coredata.citation-count');

                    if ($hIndex !== null) {
                        return [
                            'h_index' => (int) $hIndex,
                            'citations' => $citationCount !== null ? (int) $citationCount : null,
                            'source' => 'Elsevier API',
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Scopus API call failed for {$scopusId}: " . $e->getMessage());
            }
        }

        // 2. Scraping da página pública do Scopus (estrutura oficial de classes e data-testid)
        try {
            $url = "https://www.scopus.com/authid/detail.uri?authorId=" . urlencode($scopusId);
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
                    'Accept-Language' => 'en-US,en;q=0.9',
                ])
                ->get($url);

            if ($response->successful()) {
                $html = $response->body();

                $metrics = [
                    'h_index' => null,
                    'citations' => null,
                    'documents' => null,
                    'source' => 'Scopus Public Profile',
                ];

                // Extrai Citations: data-testid="metrics-section-citations-count" -> <span data-testid="unclickable-count">92</span>
                if (preg_match('/data-testid="metrics-section-citations-count".*?data-testid="unclickable-count"[^>]*>(\d+)<\/span>/is', $html, $m)) {
                    $metrics['citations'] = (int) $m[1];
                }

                // Extrai Documents: data-testid="metrics-section-document-count" -> <span data-testid="unclickable-count">33</span>
                if (preg_match('/data-testid="metrics-section-document-count".*?data-testid="unclickable-count"[^>]*>(\d+)<\/span>/is', $html, $m)) {
                    $metrics['documents'] = (int) $m[1];
                }

                // Extrai H-Index: data-testid="metrics-section-h-index" -> <span data-testid="unclickable-count">5</span>
                if (preg_match('/data-testid="metrics-section-h-index".*?data-testid="unclickable-count"[^>]*>(\d+)<\/span>/is', $html, $m)) {
                    $metrics['h_index'] = (int) $m[1];
                }

                // Fallback via JSON ou padrão inline se presente
                if ($metrics['h_index'] === null && preg_match('/\"hIndex\":\s*\"?(\d+)\"?/i', $html, $m)) {
                    $metrics['h_index'] = (int) $m[1];
                }
                if ($metrics['citations'] === null && preg_match('/\"citationCount\":\s*\"?(\d+)\"?/i', $html, $m)) {
                    $metrics['citations'] = (int) $m[1];
                }

                if ($metrics['h_index'] !== null || $metrics['citations'] !== null) {
                    return $metrics;
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Scopus public scraping failed for {$scopusId}: " . $e->getMessage());
        }

        return null;
    }
}
