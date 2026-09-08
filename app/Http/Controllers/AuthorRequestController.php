<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ResponseController;

class AuthorRequestController extends ResponseController
{
    //

    public function getAuthorsInfo()
    {
        if (Auth::user()->type == "administrative") {
            $authors = Author::with(["userInformation:id,name,ciencia_vitae,email,type"])->whereHas("userInformation", function ($query) {
                $query->where("is_active", "=", "1")->where("type", "!=", "administrative");
            })->get();

            return $this->sendResponse($authors, "", 'Todos os autores!');
        }
    }
}
