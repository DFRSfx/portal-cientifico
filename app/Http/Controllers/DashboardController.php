<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index()
    {
        $user = auth()->user();

        if ($user->type === "administrative") {
            return redirect()->route("statistics");
        }

        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('open_verify_email', true);
        }

        $author = Author::firstOrCreate(
            ['user_id' => $user->id],
            [
                'orcid' => '',
                'id_google_scholar' => '',
                'id_researcher' => '',
                'id_scopus_author' => '',
                'resume' => '',
                'profile_image_is_public' => 0,
                'profile_is_public' => 0
            ]
        );

        return redirect()->route("authors.show", $author->id);
    }

}
