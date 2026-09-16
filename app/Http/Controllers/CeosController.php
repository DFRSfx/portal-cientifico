<?php

namespace App\Http\Controllers;

use DateTime;
use App\Exports\Test;
use App\Models\Author;
use App\Models\EventAdministration;
use Illuminate\Support\Carbon;
use App\Models\EventParticipation;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CeosController extends Controller
{
    public function yearDates()
    {
        $fromDate = Carbon::now()->subYears()->year;
        $toDate = Carbon::now()->year;
        return [$fromDate, $toDate];
    }

    public function authorOutput($authorId = null)
    {
        list($fromDate, $toDate) = $this->yearDates();
        
        $authorId = $authorId ?? Auth::user()->authorInformation->id;

        $author = Author::with([
            "output" => function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('year', [$fromDate, $toDate]);
            },
            "output.type"
        ])->find($authorId);

        return collect([$author]);
    }

    public function authorProjects($authorId = null)
    {
        list($fromDate, $toDate) = $this->yearDates();
        
        $authorId = $authorId ?? Auth::user()->authorInformation->id;

        $authorProjects = Author::with([
            "project" => function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('start_date_year', [$fromDate, $toDate]);
            }
        ])->find($authorId);

        return collect([$authorProjects]);
    }

    public function authorEvents($authorId = null)
    {
        list($fromDate, $toDate) = $this->yearDates();
        
        $authorId = $authorId ?? Auth::user()->authorInformation->id;

        $authorEvents = EventParticipation::with([
            'service' => function ($query) use ($fromDate, $toDate) {
                $query->where("service_type_class", '=', 'App\Models\EventParticipation')
                      ->whereBetween('start_date_year', [$fromDate, $toDate]);
            },
            'service.author' => function ($query) use ($authorId) {
                $query->where('author.user_id', $authorId);
            }
        ])->pluck('event_name');

        return collect([$authorEvents]);
    }

    public function authorEventsAdmin($authorId = null)
    {
        list($fromDate, $toDate) = $this->yearDates();
        
        $authorId = $authorId ?? Auth::user()->authorInformation->id;

        $authorEventsAdmin = EventAdministration::with([
            'service' => function ($query) use ($fromDate, $toDate) {
                $query->where("service_type_class", '=', 'App\Models\EventAdministration')
                      ->whereBetween('start_date_year', [$fromDate, $toDate]);
            },
            'service.author' => function ($query) use ($authorId) {
                $query->where('author.user_id', $authorId);
            }
        ])->pluck('event_description');

        return collect([$authorEventsAdmin]);
    }

public function authorSupervision() //para o N4 todas as atividades feito
    {
        list($fromDate) = $this->yearDates();
        $authorSupervision = [];
        $authorEvents = Author::with([
            "service.polymorphic",
            "service" => function ($query) use ($fromDate) {
                $query->orderBy("end_year", "DESC")
                    ->where('service_type_class', '=', 'App\Models\Supervision')
                    ->where('end_year', [$fromDate]);
            },
            "service.type"
        ])->findOrFail(Auth::user()->authorInformation->id);
        foreach ($authorEvents->service as $service) {
            if ($service->polymorphic) {
                // Do something with $polymorphicAttributes
                $authorSupervision[] = $service->polymorphic->toArray();
            }
        }


        return [$authorSupervision];
    }


  public function authorConferenceComitee() {

    list($fromDate) = $this->yearDates();

    $authorConferenceComitte = EventParticipation::whereHas('service', function ($query) use ($fromDate) {
        $query->where('author_id', Auth::user()->authorInformation->id)
            ->where('service_type_class', '=', 'App\Models\CommiteeMembership')
            ->where('start_year', [$fromDate]);
    })->get()
        ->pluck('event_name')
        ->unique();

    return [$authorConferenceComitte];

}

   private function collectAuthorData($authorId)
{
    $data = [
        'outputs' => [],
        'projects' => [],
        'events' => [],
        'eventsAdmin' => []
    ];

    list($author) = $this->authorOutput($authorId);
    list($authorProjects) = $this->authorProjects($authorId);
    list($authorEvents) = $this->authorEvents($authorId);
    list($authorEventsAdmin) = $this->authorEventsAdmin($authorId);

    if ($author && $author->output) {
        foreach ($author->output as $output) {
            $data['outputs'][] = $output->citation_string . "-" . $output->year . "-" . $output->title . "-" . $output->doi;
        }
    }

    if ($authorProjects && $authorProjects->project) {
        foreach ($authorProjects->project as $project) {
            $data['projects'][] = $project->project_title;
        }
    }

    if ($authorEvents) {
        foreach ($authorEvents as $event) {
            $data['events'][] = $event;
        }
    }

    if ($authorEventsAdmin) {
        foreach ($authorEventsAdmin as $eventAdmin) {
            $data['eventsAdmin'][] = $eventAdmin;
        }
    }

    return $data;
}

  public function exportOneAuthor()
    {
        $this->fromDate = request('year');
        $concatOutputs = [];
        $concatProjects = [];
        $concatEvents = [];
        $concatEventsAdmin = [];
        $concatOutputTypes = [];

        list($author) = $this->authorOutput();
        list($authorProjects) = $this->authorProjects();
        list($authorEvents) = $this->authorEvents();
        list($merged) = $this->authorEventsAdmin();
        list($authorSupervision) = $this->authorSupervision();

        foreach ($author->output as $output) {
            $formattedOutput = $output->citation_string . " (" . $output->year . ") " . $output->title . ". " . $output->doi;
            array_push($concatOutputs, $formattedOutput);
        }

        foreach ($author->output as $output) {
            array_push($concatOutputTypes,  $output->output_type_class);
        }

        foreach ($authorProjects->project as $project) {
            array_push($concatProjects,  $project->project_title);
        }
        foreach ($authorEvents as $event) {
            array_push($concatEvents,  $event);
        }

        $date =  date('d-m-Y');
        $options = array_map(function ($outputType) {
            return match (true) {
                $outputType == "App\Models\JournalArticle" => "Artigo em revista científica",
                $outputType == "App\Models\ConferencePaper"  => "Artigo em conferência",
                $outputType == "App\Models\MagazineArticles"  => "Artigo em revista científica",
                $outputType == "App\Models\ConferencePoster"  => "Poster em conferência",
                $outputType == "App\Models\Book"  => "Livro, Antologia, Número especial de uma revista",
                $outputType == "App\Models\BookChapter"  => "Livro, Antologia, Número especial de uma revista",
                $outputType == "App\Models\ConferenceAbstract"  => "Resumo em atas de conferência",
                default => "Outro",
            };
        }, $concatOutputTypes);


        $dateOriginal = new DateTime();
        $dateOriginal->modify('-1 year');
        $templatePath = storage_path('app/public/FirminoSilva_RelatorioIndividual_2023.xlsx');
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', __('O modelo de relatório individual CEOS (:file) não foi encontrado no servidor. Por favor contacte a administração.', ['file' => 'FirminoSilva_RelatorioIndividual_2023.xlsx']));
        }

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($templatePath);

        $rawName = (string) Auth::user()->name;
        $safeName = preg_replace('/[^A-Za-z0-9 _-]/', '', $rawName);
        $filename = 'Avaliacao de ' . trim($safeName) . ' ' . $date . '.xlsx';

        $columnArray = array_chunk($concatOutputs, 1);
        $columnArray2 = array_chunk($concatProjects, 1);
        $columnArray3 = array_chunk($concatEvents, 1);
        $columnArray4 = $merged;
        $conferenceArray = [];
        $thesisTitles = array_map(function ($item) {
            return [$item['thesis_title']];
        }, $authorSupervision);
        foreach ($columnArray4 as $item) {
            // Check if both 'conference' and 'event_description' exist
            if (isset($item['conference']) && isset($item['event_description'])) {
                $conferenceArray[$item['event_description']] = $item['conference'];
            } elseif (isset($item['conference'])) {
                $conferenceArray[] = $item['conference'];
            } elseif (isset($item['event_description'])) {
                $conferenceArray[] = $item['event_description'];
            } elseif (isset($item['theme'])) {
                $conferenceArray[] = $item['theme'];
            }
        }



       
        $conferenceArray = array_map(function ($conference) {
            return [$conference];  // Each element in the array becomes an array (one element per row)
        }, $conferenceArray);
        $spreadsheet->getSheetByName('DadosN1')->fromArray($columnArray, NULL, 'B2');
        $spreadsheet->getSheetByName('DadosN1')->fromArray(array_map(fn($option) => [$option], $options), NULL, 'C2');
        $spreadsheet->getSheetByName('DadosN2')->fromArray($columnArray2, NULL, 'B2');
        // $spreadsheet->getSheetByName('DadosN3')->fromArray($columnArray3, NULL, 'B2');
        $spreadsheet->getSheetByName('DadosN4')->fromArray($conferenceArray, NULL, 'B2');
        $spreadsheet->getSheetByName('DadosN5')->fromArray($thesisTitles, NULL, 'B2');


     
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getProtection()->setPassword('CEOS.PP');
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setSort(true);
        $sheet->getProtection()->setInsertRows(true);
        $sheet->getProtection()->setFormatCells(true);

    
        $unprotectedCells = ['B2'];
        foreach ($unprotectedCells as $cell) {
            $sheet->getStyle($cell)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        }

     
        $response = new StreamedResponse(function () use ($spreadsheet) {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output'); 
        }, 200);

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;  filename="' . $filename . '"');
        return $response;
}public function exportAllAuthors()
{
    set_time_limit(600);
    ini_set('memory_limit', '1024M');

    $date = date('d-m-Y');

    $authors = Author::with([
        'output',
        'output.type',
        'output.polymorphic',
        'project'
    ])->get();
    
    $userIds = $authors->pluck('user_id')->filter()->toArray();
    
    $templatePath = storage_path('app/public/FirminoSilva_RelatorioIndividual_2023.xlsx');
    if (!file_exists($templatePath)) {
        return redirect()->back()->with('error', __('O modelo de relatório individual CEOS (:file) não foi encontrado no servidor. Por favor contacte a administração.', ['file' => 'FirminoSilva_RelatorioIndividual_2023.xlsx']));
    }

    $reader = IOFactory::createReader('Xlsx');
    $spreadsheet = $reader->load($templatePath);

    $sheetN1 = $spreadsheet->getSheetByName('DadosN1');
    $sheetN2 = $spreadsheet->getSheetByName('DadosN2');
    $sheetN3 = $spreadsheet->getSheetByName('DadosN3');
    $sheetN4 = $spreadsheet->getSheetByName('DadosN4');

    $rowN1 = 2;
    $rowN2 = 2;
    $rowN3 = 2;
    $rowN4 = 2;

    foreach ($authors as $author) {
        if (!isset($users[$author->user_id])) {
            continue;
        }
        
        $authorName = $users[$author->user_id]->name;

        try {
            if ($author->output && $author->output->count() > 0) {
                foreach ($author->output as $output) {
                    $outputType = get_class($output->polymorphic);
                    
                    \Log::info('Output type', [
                        'author_id' => $author->id,
                        'output_id' => $output->id,
                        'polymorphic_type' => $output->polymorphic_type,
                        'polymorphic_class' => $outputType
                    ]);
                    
                    $outputCategory = match ($outputType) {
                        "App\Models\JournalArticle" => "Artigo em revista cient�fica",
                        "App\Models\ConferencePaper" => "Artigo em confer�ncia",
                        "App\Models\MagazineArticles" => "Artigo em revista cient�fica",
                        "App\Models\ConferencePoster" => "Poster em confer�ncia",
                        "App\Models\Book" => "Livro, Antologia, N�mero especial de uma revista",
                        "App\Models\BookChapter" => "Livro, Antologia, N�mero especial de uma revista",
                        "App\Models\ConferenceAbstract" => "Resumo em atas de confer�ncia",
                        default => "Outro",
                    };
                    
                    $sheetN1->setCellValue('A' . $rowN1, $authorName);
                    $sheetN1->setCellValue('B' . $rowN1, 
                        $output->citation_string . "-" . $output->year . "-" . $output->title . "-" . $output->doi
                    );
                    $sheetN1->setCellValue('C' . $rowN1, $outputCategory);
                    $rowN1++;
                }
            }

            if ($author->project && $author->project->count() > 0) {
                foreach ($author->project as $project) {
                    $sheetN2->setCellValue('A' . $rowN2, $authorName);
                    $sheetN2->setCellValue('B' . $rowN2, $project->project_title);
                    $rowN2++;
                }
            }

        } catch (\Exception $e) {
            \Log::error('Error processing author ' . $authorName . ': ' . $e->getMessage());
            continue;
        }
    }

    foreach ([$sheetN1, $sheetN2, $sheetN3, $sheetN4] as $sheet) {
        $sheet->getProtection()->setPassword('CEOS.PP');
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setSort(true);
        $sheet->getProtection()->setInsertRows(true);
        $sheet->getProtection()->setFormatCells(true);
    }

    $filename = 'Avaliacoes_Todos_Autores_' . $date . '.xlsx';

    $response = new StreamedResponse(function () use ($spreadsheet) {
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }, 200);

    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
    return $response;
}
}
