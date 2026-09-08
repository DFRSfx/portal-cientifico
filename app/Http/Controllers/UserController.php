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
use Illuminate\Validation\Rule;

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

        $users = User::where("is_active", "=", 0)
            ->select("created_at", "id", "name", "email", "type", "ciencia_vitae", "email_verified_at")
            ->get();

        return view("user.inactive-users", compact("users"));
    }

    public function edit($userId)
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $user = User::find($userId);

        if (!$user) {
            return Redirect::back()->withErrors(["errorMessage" => "Resource not found"]);
        }

        return view('user.edit', compact('user'));
    }

    public function update(Request $request, $userId)
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $user = User::find($userId);

        if (!$user) {
            return Redirect::back()->withErrors(["errorMessage" => "Resource not found"]);
        }

        $dataValidated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'type' => ['required', 'string', 'max:255'],
            'entidade' => ['nullable', 'string', 'max:255'],
            'ciencia_vitae' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'in:0,1,on,off,true,false'],
            'is_isla' => ['nullable', 'in:0,1,on,off,true,false'],
            'mark_verified' => ['nullable', 'in:0,1,on,off,true,false'],
        ]);

        $user->name = $dataValidated['name'];
        $user->email = $dataValidated['email'];
        $user->type = $dataValidated['type'];
        $user->entidade = $dataValidated['entidade'] ?? null;
        $user->ciencia_vitae = $dataValidated['ciencia_vitae'] ?? null;
        $user->is_active = $request->boolean('is_active');
        $user->is_isla = $request->boolean('is_isla');

        if ($request->boolean('mark_verified')) {
            $user->email_verified_at = now();
        }

        $user->save();

        $redirectTo = $request->input('redirect_to');

        return $redirectTo
            ? Redirect::to($redirectTo)->with('success', 'Utilizador atualizado com sucesso')
            : Redirect::route('user.active')->with('success', 'Utilizador atualizado com sucesso');
    }

    public function getAllActiveUsers()
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $users = User::where("is_active", "=", 1)
            ->with("authorInformation:id,user_id")
            ->select("created_at", "id", "name", "email", "type", "ciencia_vitae", "email_verified_at")
            ->orderBy('name')
            ->get();

        return view("user.active-users", compact("users"));
    }

    public function activate($userId)
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $user = User::find($userId);

        if (!$user) {
            return Redirect::back()->withErrors(["errorMessage" => "Resource not found"]);
        }

        $user->is_active = 1;
        $user->save();

        return Redirect::back()->with("success", "Utilizador aprovado com sucesso");
    }

    public function deactivate($userId)
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $user = User::find($userId);

        if (!$user) {
            return Redirect::back()->withErrors(["errorMessage" => "Resource not found"]);
        }

        $user->is_active = 0;
        $user->save();

        return Redirect::back()->with("success", "Utilizador desativado com sucesso");
    }

    public function resendVerification($userId)
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $user = User::find($userId);

        if (!$user) {
            return Redirect::back()->withErrors(["errorMessage" => "Resource not found"]);
        }

        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        return Redirect::back()->with("success", "Email de verificacao reenviado");
    }

    public function destroy($userId)
    {
        abort_if(auth()->user()->type != "administrative", 403);

        $user = User::find($userId);

        if (!$user) {
            return Redirect::back()->withErrors(["errorMessage" => "Resource not found"]);
        }

        // Prevent deleting own account to avoid locking out the current admin
        if ($user->id === auth()->id()) {
            return Redirect::back()->withErrors(["errorMessage" => "Nao pode remover a sua propria conta"]);
        }

        $user->delete();

        return Redirect::back()->with("success", "Utilizador removido com sucesso");
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
