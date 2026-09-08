<?php

namespace App\Exports;

use App\Models\Author;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;

class AuthorSheet implements FromCollection,WithMapping, WithTitle
{
    private $author;

    public function __construct($author)
    {
        $this->author = $author;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Author::with(["userInformation:id,name,email,ciencia_vitae", "output"])->find($this->author->id);
    }

    public function map($dsadasd): array
    {
        dd($dsadasd);

        return [
            $dsadasd->output->title
        ];
    }

    public function title(): string
    {
        return "dsadasdasd";
    }

}
