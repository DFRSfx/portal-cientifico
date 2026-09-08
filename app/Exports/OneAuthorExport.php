<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Concerns\FromCollection;

class OneAuthorExport
{
    protected $spreadsheet;

    public function __construct()
    {
        // Load the template file (adjust the path)

        $this->spreadsheet = IOFactory::load(storage_path('\app\public\Avaliação Pessoal Docente.xlsx'));
    }
   public function map(): array
    {
        $sheet = $this->spreadsheet->getActiveSheet();
        return [
           $sheet->setCellValue('D8', '=SUM(B2:B7)')
            // Add more cell mappings as needed...
        ];
        return $sheet;
    } 
    /* public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Apply bold font to cell A4
                $event->sheet->getStyle('A4')->getFont()->setBold(true);
                // Set a formula in cell D8

            },
        ];
    } */
}
