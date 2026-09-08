<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Author;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\UserCreationRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserCreationNotification;

class UserController extends Controller
{
    public function create()
    {
        abort_if(auth()->user()->type != "administrative", 403);

        return view("user.create");
    }
    public function import(Request $request)
    {
        abort_if(auth()->user()->type != "administrative", 403);
        $file = $request->file('file');
        Excel::import(new UsersImport, $file);
        return redirect()->back()->with('success', 'Users imported successfully!');
    }
    public function store(UserCreationRequest $request)
    {
        $dataValidated = $request->validated();
        abort_if(auth()->user()->type != "administrative", 403);

        $newUser = new User();

        $newUser->name = $dataValidated["name"];
        $newUser->email = $dataValidated["email"];
        $newUser->password = Hash::make($dataValidated["ciencia_vitae"]);
        $newUser->ciencia_vitae = $dataValidated["ciencia_vitae"] ?? "";
        $newUser->type = $dataValidated["type"];
        $newUser->is_admin = 0;
        $newUser->set_password_token = $this->generatePasswordToken();
        $newUser->is_active = 1;
        $newUser->is_isla = $dataValidated["is_isla"];

        $success = $newUser->save();
        if ($success) {
            // associates the author information with the new user
            if ($dataValidated["type"] != "administrative") {
                $newUser->authorInformation()->create([
                    'orcid' => "",
                    'id_google_scholar' => "",
                    'id_researcher' => "",
                    'id_scopus_author' => "",
                    "resume" => "",
                    "profile_image_is_public" => 0,
                    "profile_is_public" => 0
                ]);
            }

            // // Sends a notifications to the user
            Notification::send($newUser, new UserCreationNotification($newUser->set_password_token));

            return Redirect::back()->with("success", "Utilizador criado com successo");
        }

        return Redirect::back()->withErrors(["errorMessage" => "Error!"])->withInput();
    }

    /*

    Inactive Users

    */
    public function getAllInactiveUsers()
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $users = User::where("is_active", "=", 1)->where('is_isla', 1)->select("name","created_at", "id", "email", "type", "ciencia_vitae")->get();

        return view("user.inactive-users", compact("users"));
    }

    public function reesendUserToken($userId)
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $userToSendNotification = User::where("is_active", "=", 0)->find($userId);

        if ($userToSendNotification) {
            Notification::send($userToSendNotification, new UserCreationNotification($userToSendNotification->set_password_token));

            return Redirect::back()->with("success", "Sucesso");
        }

        return Redirect::back()->withErrors(["errorMessage" => "Resorce not found"]);
    }

    private function generatePasswordToken()
    {
        $generatedHashExists = true;

        while ($generatedHashExists) {
            $generatedHash = bin2hex(random_bytes(15));

            $user = User::where("set_password_token", "=", $generatedHash)->first();

            if (!$user) {
                $generatedHashExists = false;
            }
        }

        return $generatedHash;
    }
}
