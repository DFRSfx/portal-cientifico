<?php

namespace App\Jobs;

use App\Models\Author;
use App\Models\CvUpdateRun;
use App\Http\Controllers\CienciaVitaeController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAllAuthorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600;
    public int $tries = 3;

    public function __construct(private int $runId, private int $batchSize = 10)
    {
    }

    public function handle(): void
    {
        $run = CvUpdateRun::find($this->runId);
        if (!$run) {
            return;
        }

        $run->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        $api = new CienciaVitaeController();

        $updated = 0;
        $failed = 0;
        $skipped = 0;
        $private = 0;
        $processed = 0;
        $lastId = (int) ($run->last_id ?? 0);
        $errors = [];

        $total = Author::whereHas('userInformation', function ($query) {
            $query->whereNotNull('ciencia_vitae');
        })->count();

        $run->update(['total' => $total]);

        while (true) {
            $authors = Author::where('id', '>', $lastId)
                ->orderBy('id')
                ->with('userInformation')
                ->limit($this->batchSize)
                ->get();

            if ($authors->isEmpty()) {
                break;
            }

            foreach ($authors as $author) {
                $lastId = $author->id;
                $processed++;

                try {
                    if (!$author->userInformation || !$author->userInformation->ciencia_vitae) {
                        $skipped++;
                        continue;
                    }

                    $authorCurriculumIsPublic = $api->authorCurriculumIsPub($author->userInformation->ciencia_vitae);

                    if ($authorCurriculumIsPublic === 1) {
                        sleep(1);
                        $endPoint = 'curriculum/' . urlencode($author->userInformation->ciencia_vitae);
                        $responseArray = $api->cienciaVitaeRequest($endPoint);

                        if (gettype($responseArray) !== 'array') {
                            $failed++;
                            $errors[] = "Author {$author->id}: invalid API response";
                            continue;
                        }

                        $response = $api->updateAuthorInformation($responseArray, $author);

                        if (!empty($response['error'])) {
                            $failed++;
                            $errors[] = "Author {$author->id}: {$response['message']}";
                            continue;
                        }

                        $updated++;
                    } elseif ($authorCurriculumIsPublic === 0) {
                        $author->update([
                            'profile_is_public' => 0,
                            'profile_image_is_public' => 0,
                            'profile_updated_date' => null,
                            'orcid' => '',
                            'id_google_scholar' => '',
                            'id_researcher' => '',
                            'id_scopus_author' => '',
                            'researchgate_profile' => '',
                            'id_lattes' => '',
                            'resume' => ''
                        ]);

                        $private++;
                    } else {
                        $failed++;
                        $errors[] = "Author {$author->id}: error checking curriculum status";
                    }
                } catch (\Throwable $e) {
                    $failed++;
                    $errors[] = "Author {$author->id}: {$e->getMessage()}";
                }

                $run->update([
                    'processed' => $processed,
                    'updated' => $updated,
                    'failed' => $failed,
                    'skipped' => $skipped,
                    'private' => $private,
                    'last_id' => $lastId,
                    'errors' => array_slice($errors, 0, 10),
                ]);
            }
        }

        $run->update([
            'status' => 'completed',
            'finished_at' => now(),
        ]);
    }
}
