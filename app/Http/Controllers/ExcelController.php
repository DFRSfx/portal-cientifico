<?php

namespace App\Http\Controllers;

use DateTime;
use App\Exports\Test;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Exports\OneAuthorExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Http\Controllers\Exports\ExcelExport;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExcelController extends Controller
{
    public function yearDates()
    {
        $fromDate = Carbon::now()->subYears(5)->startOfYear();
        $toDate = Carbon::now()->endOfYear();
        return [$fromDate, $toDate];
    }
    public function authorOutput()
    {
        list($fromDate, $toDate) = $this->yearDates();
        $author = Author::with(["output" => function ($query) use ($fromDate, $toDate) {
            $query->whereBetween('year', [$fromDate, $toDate]);
        }, "output.type"])->findOrFail(Auth::user()->authorInformation->id);
        $publisherArrayConferencePaper = $author->output->where("output_type_class", '=', 'App\Models\ConferencePaper')->pluck('publisher');
        $publisherArrayJornalArcticleMagazine = $author->output->where("output_type_class", '=', 'App\Models\ConferencePaper')->where("output_type_class", '=', 'App\Models\JournalArticle')->pluck('url')->toArray();
        $lastParts = array_map(function ($url) {
            $parts = explode(' ', $url);
            return end($parts);
        }, $publisherArrayJornalArcticleMagazine);
        $outCount = count($author->output);
        $booksCount = count($author->output->where("output_type_class", '=', 'App\Models\Book'));
        $bookChapterCount = count($author->output->where("output_type_class", '=', 'App\Models\BookChapter'));
        $magazineCount = count($author->output->where("output_type_class", '=', 'App\Models\MagazineArcticles'));
        $conferencePaperCount = count($author->output->where("output_type_class", '=', 'App\Models\ConferencePaper'));
        $indexedMagazinesCount = $author->output
            ->where(function ($query) use ($lastParts) {
                $query->where("output_type_class", '=', 'App\Models\MagazineArticles')
                    ->orWhere("output_type_class", '=', 'App\Models\JournalArticle');
            });

        foreach ($lastParts as $part) {
            $indexedMagazinesCount->orWhere('url', 'like', '%' . $part . '%');
        }

        $indexedMagazinesCount = $indexedMagazinesCount->count();

        $indexedConferencesCount = count($author->output->where("output_type_class", '=', 'App\Models\ConferencePaper', function ($query) use ($publisherArrayConferencePaper) {
            $query->whereIn('publisher',  $publisherArrayConferencePaper);
        }));

        return [$outCount, $booksCount, $bookChapterCount, $magazineCount, $conferencePaperCount, $indexedConferencesCount, $indexedMagazinesCount];
    }
    public function authorProject()
    {
        list($fromDate, $toDate) = $this->yearDates();

        $authorProjects = Author::with(["project" => function ($query) use ($fromDate, $toDate) {
            $query->whereBetween('year_awarded', [[$fromDate, $toDate]]);
        }])->findOrFail(Auth::user()->authorInformation->id);
        $authorProjectsCount = $authorProjects->count();
        $authorProjectsParticipations = $authorProjects->project->pluck('project_title')->toArray(); //leader of research

        $authorResearchLeader = $authorProjects->project->where("investigation_role", '=', 'Principal investigator')->pluck('project_title')->toArray(); //leader of research
        $authorResearchLeaderCount = count($authorProjects->project->where("investigation_role", '=', 'Principal investigator')); //leader of research
        return [$authorResearchLeaderCount, $authorProjects, $authorProjectsCount, $authorResearchLeader, $authorProjectsParticipations];
    }
    public function exportAuthors()
    {
        return Excel::download(new Test(), 'authors.xlsx');
    }

    public function exportOneAuthor()
    {        //retrieve the author projects
        list($authorResearchLeaderCount, $authorProjects, $authorProjectsCount, $authorResearchLeader, $authorProjectsParticipations) = $this->authorProject();
        list($outCount, $booksCount, $bookChapterCount, $magazineCount, $conferencePaperCount, $indexedConferencesCount, $indexedMagazinesCount) = $this->authorOutput();
        $rawName = (string) Auth::user()->name;
        $safeName = preg_replace('/[^A-Za-z0-9 _-]/', '', $rawName);
        $filename = 'Avaliacao de ' . trim($safeName) . '.xlsx';
        // $option = match (true) {
        //     $outCount >= 1 && $outCount <= 5 => "1 a 5",
        //     $outCount >= 6 && $outCount <= 10 => "6 a 10",
        //     $outCount >= 11 && $outCount <= 15 => "11 a 15",
        //     $outCount > 15 => "  > 15",
        //     default => "Nenhum(a)",
        // };
        $option2 = match (true) {
            $magazineCount >= 1 && $magazineCount <= 2 => "1 a 2",
            $magazineCount == 3  => "3",
            $magazineCount == 4  => "4",
            $magazineCount >= 5  => " ≥ 5",
            default => "Nenhum(a)",
        };
        $option3 = match (true) {
            $conferencePaperCount >= 1 && $conferencePaperCount <= 2 => "1 a 2",
            $conferencePaperCount == 3  => "3",
            $conferencePaperCount == 4  => "4",
            $conferencePaperCount >= 5  => " ≥ 5",
            default => "Nenhum(a)",
        };

        $option4 = match (true) {
            $magazineCount + $conferencePaperCount >= 1 && $magazineCount + $conferencePaperCount <= 2 => "1 a 2",
            $magazineCount + $conferencePaperCount == 3  => "3",
            $magazineCount + $conferencePaperCount == 4  => "4",
            $magazineCount + $conferencePaperCount >= 5  => " ≥ 5",
            default => "Nenhum(a)",
        };
        $option5 = match (true) {
            $bookChapterCount >= 1 && $bookChapterCount <= 2 => "1 a 2",
            $bookChapterCount == 3  => "3",
            $bookChapterCount == 4  => "4",
            $bookChapterCount >= 5  => " ≥ 5",
            default => "Nenhum(a)",
        };
        $option6 = match (true) {
            $booksCount >= 1 && $booksCount <= 2 => "1 a 2",
            $booksCount == 3  => "3",
            $booksCount == 4  => "4",
            $booksCount >= 5  => " ≥ 5",
            default => "Nenhum(a)",
        };
        if ($authorResearchLeaderCount) {
            $option7 = "Sim";
        } else {
            $option7 = "Não";
        }
        if ($authorProjects) {
            $option8 = "Sim";
        } else {
            $option8 = "Não";
        }

        $option9 = match (true) {
            $authorProjectsCount >= 1 && $authorProjectsCount <= 2 => "1 a 2",
            $authorProjectsCount > 2 => ">2",
            default => "Nenhum(a)",
        };
        $option10 = match (true) {
            $outCount == 1  => "1",
            $outCount >= 2  => " ≥ 2",

            default => "Nenhum(a)",
        };
        $option11 = match (true) {
            $indexedMagazinesCount >= 1 && $indexedMagazinesCount <= 2 => "1 a 2",
            $indexedMagazinesCount == 3  => "3",
            $indexedMagazinesCount == 4  => "4",
            $indexedMagazinesCount >= 5  => " ≥ 5",
            default => "Nenhum(a)",
        };
        $option12 = match (true) {
            $indexedConferencesCount >= 1 && $indexedConferencesCount <= 2 => "1 a 2",
            $indexedConferencesCount == 3  => "3",
            $indexedConferencesCount == 4  => "4",
            $indexedConferencesCount >= 5  => " ≥ 5",
            default => "Nenhum(a)",
        };
        $date =  date('d-m-Y');
        // Create a DateTime object for the current date
        $dateOriginal = new DateTime();

        // Subtract one year from the current date
        $dateOriginal->modify('-1 year');

        // Format the date to get the month and year
        $previousMonthYear = $dateOriginal->format('M-Y');
        $previousMonthYear = strtolower($previousMonthYear);


        $templatePath = storage_path('app/public/Avaliação Pessoal Docente.xlsx');
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', __('O modelo de avaliação docente (:file) não foi encontrado no servidor. Por favor contacte a administração.', ['file' => 'Avaliação Pessoal Docente.xlsx']));
        }

        $columnArray = array_chunk($authorResearchLeader, 1);
        $columnArray2 = array_chunk($authorProjectsParticipations, 1);
        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($templatePath);
        $spreadsheet->getActiveSheet()->setCellValue('L51', Auth::user()->ciencia_vitae);
        $spreadsheet->getActiveSheet()->setCellValue('L54', "Nenhum(a)");
        $spreadsheet->getActiveSheet()->setCellValue('L61', $option4);
        $spreadsheet->getActiveSheet()->setCellValue('L63', $option5);
        $spreadsheet->getActiveSheet()->setCellValue('L65', $option6);
        $spreadsheet->getActiveSheet()->setCellValue('L67', $option10);
        $spreadsheet->getActiveSheet()->setCellValue('L79', $option7);
        $spreadsheet->getActiveSheet()->setCellValue('L81', $option8);
        $spreadsheet->getActiveSheet()->setCellValue('L57', $option11);
        $spreadsheet->getActiveSheet()->setCellValue('L59', $option12);

        $spreadsheet->getActiveSheet()->setCellValue('L188', $date);
        $spreadsheet->getActiveSheet()->setCellValue('H7', $previousMonthYear);

        $spreadsheet->getActiveSheet()->setCellValue('L85', 'Não');
        $spreadsheet->getActiveSheet()->setCellValue('B188', Auth::user()->name);
        $spreadsheet->getActiveSheet()->setCellValue('H9', Auth::user()->name);

        $spreadsheet->getActiveSheet()->fromArray($columnArray, NULL, 'B126');
        $spreadsheet->getActiveSheet()->fromArray($columnArray2, NULL, 'B135');
        //get all spreadsheets

        $spreadsheet->getActiveSheet()->getProtection()->setPassword('QUALIDADEISLA');
        $spreadsheet->getActiveSheet()->getProtection()->setSheet(true);
        $spreadsheet->getActiveSheet()->getProtection()->setSort(true);
        $spreadsheet->getActiveSheet()->getProtection()->setInsertRows(true);
        $spreadsheet->getActiveSheet()->getProtection()->setFormatCells(true);


        // Unprotect specific cells
        $unprotectedCells = ['L51', 'L57', 'L59', 'L61', 'L63', 'L65', 'L67', 'L79', 'L81', 'L83', 'L188', 'L85', 'B188', 'B126', 'B135', 'B127', 'B128', 'B129', 'B130', 'B131', 'B132', 'B135', 'B136', 'B137', 'B138', 'B139', 'B140', 'B141', 'L14', 'L16', 'L18', 'L20', 'L88', 'L90', 'L92', 'L95', 'L97', 'L99', 'L101', 'L104', 'L106', 'L113', 'L115', 'L117', 'L119', 'L121'];
        foreach ($unprotectedCells as $cell) {
            $spreadsheet->getActiveSheet()->getStyle($cell)->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        }
        $response = new StreamedResponse(function () use ($filename, $spreadsheet) {
            // Set headers
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            // Create Xlsx writer and output the file directly to the client
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }, 200);
        return $response;
    }
}
