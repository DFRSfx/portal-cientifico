<?php

namespace App\Http\Controllers\Auth;

use Rules\Password;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\setPasswordRequest;

class SetPasswordController extends Controller
{
    public function create(Request $request)
    {
        $user = User::where("set_password_token", "=", $request->token)->first();

        if(!$user)
        {
            abort(403);
        }
        else if($user->is_active)
        {
            return redirect()->route("login");
        }
        else
        {
            $requestToken = $request->token;

            return view('auth.set-password', compact("requestToken"));
        }

    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(setPasswordRequest $request)
    {
        $dataValidated = $request->validated();

        $user = User::where("set_password_token", "=", $request->token)->first();

        if($user && !$user->is_active)
        {
            $user->password = Hash::make($request->password);
            $user->is_active = 1;
            
            if($user->save())
            {
                Auth::login($user);

                return redirect()->route("dashboard");
            }
            else
            {
                return redirect()->back()->withErrors(["message"=>"Error"]);
            }
            
        }
        else
        {
            return redirect()->route("home");
        }
        
    }
}
