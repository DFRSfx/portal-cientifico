<?php

namespace App\Console\Commands;

use App\Models\Author;
use App\Services\MetricScraperService;
use Illuminate\Console\Command;

class SyncAuthorMetricsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'authors:sync-metrics {author_id? : ID específico do autor para sincronizar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza automaticamente o H-index e métricas através do Google Scholar e Scopus';

    /**
     * Execute the console command.
     */
    public function handle(MetricScraperService $scraper): int
    {
        $authorId = $this->argument('author_id');

        if ($authorId) {
            $author = Author::find($authorId);
            if (!$author) {
                $this->error("Autor #{$authorId} não encontrado.");
                return 1;
            }
            $authors = collect([$author]);
        } else {
            $authors = Author::whereNotNull('id_google_scholar')
                ->orWhereNotNull('id_scopus_author')
                ->get();
        }

        $this->info("A sincronizar métricas para " . $authors->count() . " autor(es)...");

        $updatedCount = 0;

        foreach ($authors as $author) {
            $this->line("A verificar: {$author->name} (ID: {$author->id})...");
            $result = $scraper->syncAuthorMetrics($author);

            if ($result['updated']) {
                $author->refresh();
                $this->info(" -> Atualizado! H-Index: {$author->h_index} [Fonte: {$author->h_index_source}]");
                $updatedCount++;
            } else {
                $this->warn(" -> Não foi possível obter novos dados automáticos para o autor #{$author->id}.");
            }
        }

        $this->info("Concluído! {$updatedCount} autor(es) atualizado(s).");

        return 0;
    }
}
