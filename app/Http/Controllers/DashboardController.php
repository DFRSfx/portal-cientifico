<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index()
    {
        
        if(auth()->user()->type == "administrative")
        {
            return redirect()->route("statistics");
        }
        else
        {
            $author = Author::where("user_id", "=", auth()->user()->id)->with(["userInformation:id,name,ciencia_vitae,email,type"])->first();

            if (!$author) {
                return redirect()->route('verification.notice');
            }
    
            return redirect()->route("authors.show", $author->id);
        }

    }

}
