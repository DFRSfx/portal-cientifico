<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Author;
use App\Models\Output;
use App\Http\Controllers\CienciaVitaeController;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('authors:update-cv {--batch=3} {--start=0} {--max=0}', function () {
    set_time_limit(0);
    ini_set('memory_limit', '1024M');

    $batchSize = (int) $this->option('batch');
    $lastId = (int) $this->option('start');
    $max = (int) $this->option('max');

    $cienciaVitaeApi = new CienciaVitaeController();

    $updated = 0;
    $failed = 0;
    $skipped = 0;
    $private = 0;
    $processed = 0;
    $errors = [];

    $this->info('Starting Ciencia Vitae update...');

    while (true) {
        if ($max > 0 && $processed >= $max) {
            break;
        }

        $query = Author::where('id', '>', $lastId)
            ->orderBy('id')
            ->with('userInformation');

        $authors = $query->limit($batchSize)->get();

        if ($authors->isEmpty()) {
            break;
        }

        foreach ($authors as $author) {
            if ($max > 0 && $processed >= $max) {
                break 2;
            }

            $lastId = $author->id;
            $processed++;

            try {
                if (!$author->userInformation || !$author->userInformation->ciencia_vitae) {
                    $skipped++;
                    continue;
                }

                $authorCurriculumIsPublic = $cienciaVitaeApi->authorCurriculumIsPub($author->userInformation->ciencia_vitae);

                if ($authorCurriculumIsPublic === 1) {
                    sleep(1);

                    $endPoint = 'curriculum/' . urlencode($author->userInformation->ciencia_vitae);
                    $responseArray = $cienciaVitaeApi->cienciaVitaeRequest($endPoint);

                    if (gettype($responseArray) !== 'array') {
                        $failed++;
                        $errors[] = "Author {$author->id}: invalid API response";
                        continue;
                    }

                    $response = $cienciaVitaeApi->updateAuthorInformation($responseArray, $author);

                    if ($response['error']) {
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

                    $informationRelationsToRemove = [
                        'citationName',
                        'email',
                        'activity',
                        'employments',
                        'degrees',
                        'service',
                        'output',
                        'languages',
                        'project'
                    ];

                    foreach ($informationRelationsToRemove as $relation) {
                        $author->{$relation}()->delete();
                    }

                    Output::doesntHave('authors')->delete();

                    $cienciaVitaeApi->removeAllPolymorphicRelations();

                    $private++;
                } else {
                    $failed++;
                    $errors[] = "Author {$author->id}: error checking curriculum status";
                }
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = "Author {$author->id}: {$e->getMessage()}";
            }

            gc_collect_cycles();
        }

        $this->line("Batch done. lastId={$lastId} updated={$updated} failed={$failed} skipped={$skipped} private={$private}");
    }

    $this->info("Done. lastId={$lastId} updated={$updated} failed={$failed} skipped={$skipped} private={$private}");

    if (!empty($errors)) {
        $this->warn('Sample errors:');
        foreach (array_slice($errors, 0, 10) as $err) {
            $this->line("- {$err}");
        }
    }
})->purpose('Update authors from Ciencia Vitae');
