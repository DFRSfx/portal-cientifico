<?php

namespace App\Http\Controllers\Exports;

use App\Models\Author;
use App\Exports\AuthorSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExcelExport implements WithMultipleSheets
{

    public function sheets(): array
    {
        $sheets = [];

        $authors = Author::whereHas("output")->get();

        foreach ($authors as $author) 
        {
            $sheets[] = new AuthorSheet($author);
        }

        return $sheets;
    }

    /*
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(true);
            },
        ];
    }

    */
    
}
