<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\AuthorCitationName;
use App\Models\Output;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class AdminController extends Controller
{
    public function allPublications()
    {
        $outputs = Output::with(['polymorphic', "authors", "type", "keywords"])->get();

       /* $citationNames = AuthorCitationName::join('authors', 'author_citation_names.author_id', '=', 'authors.id')
            ->select('author_citation_names.citation_name', 'authors.name')
            ->get();*/

        return view("layouts.admin", ["outputs" => $outputs]);


        /*
        try {
             $headers = [
                'Content-Type' => 'application/vnd.ms-excel',
                'Content-Disposition' => 'attachment; filename="file.xls"',
            ];

            $authors = Author::all();


                $callback = function () use ($authors, $outputs) {
                $file = fopen('php://output', 'w');

                fputcsv($file, ['id', 'name', 'email', 'ciencia_vitae', 'resume']);

                fputcsv($file, [$authors->id, $authors->name, $authors->email, $authors->ciencia_vitae, $authors->resume]);
                //this will give a "space" between the headers
                fputcsv($file,['']);

                fputcsv($file, ['id', 'title', 'doi']);

                foreach ($outputs as $output) {
                    fputcsv($file, [$output->id, $output->title, $output->doi]);
                }

                fclose($file);
            }; 
            return view("layouts.admin", ["outputs" => $outputs]);
        } catch (\Exception $e) {
            dd($e);
        }
        */
    }
}
