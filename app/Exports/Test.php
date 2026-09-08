<?php

namespace App\Exports;

use App\Models\Author;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Test implements FromCollection, ShouldAutoSize, WithEvents, WithMapping,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $authors = Author::with(["output" => function($query){
            $query->with(["type","polymorphic"])->orderBy("type_id");
        }, "output.type"])->get();

        // Transform the data as needed
        return $authors;
    }

    public function map($invoice): array
    {
        $array = [
            [
                $invoice->userInformation->name
            ]
        ];

        foreach($invoice->output as $output)
        {
            array_push($array, [
                "-",
                $output->title,
                $output->citation_string ?? "-",
                $output->year ?? "-",
                $output->type->name,
                $output->polymorphic->publisher ?? "-",
                $output->polymorphic->role ?? "-",
                $output->polymorphic->status
            ]);
        }


        // This example will return 3 rows.
        // First row will have 2 column, the next 2 will have 1 column
        return $array;
    }

    public function headings(): array
    {
        return [
            "Nome","Título", "Citações", "Ano de Publicação", "Tipo", "Publisher", "Papel", "Estado"
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(10)->setBold(true);
            },
        ];
    }
}
