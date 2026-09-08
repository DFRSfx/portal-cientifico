<?php

namespace App\Http\Controllers\Auth;

use App\Models\Author;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    private $apiAddress;
    private $clientToken;

    public function __construct()
    {
        $this->apiAddress = config('app.api_address');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        //Obtém o client_token
        $this->clientToken = Cache::get('client_credentials_token');

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // in tests the http request gives one ConnectionException
        try 
        {
            $response = Http::withBody(json_encode($data), 'application/json')
                ->withOptions([
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $this->clientToken,
                    ],
                ])
                ->post($this->apiAddress . 'login');

            // caso dê erro de autenticação, redireciona para uma página de erro
            if ($response->status() == 401) {
                return back()->withErrors(['message' => 'Unauthorized access']);
            }
        } 
        catch (ConnectionException $exception) 
        {
            return back()->withErrors(['errors' => $exception->getMessage()]);
        }

        $userData = json_decode($response->getBody()->getContents());

        $user = Author::where("user_api_id", "=", $userData->user[0]->id)->first();

        if (!$user) {
            $authorInformation = new Author();

            $authorInformation->name = $userData->user[0]->name;
            $authorInformation->email = $userData->user[0]->email;
            $authorInformation->user_api_id = $userData->user[0]->id;
            $authorInformation->type = $userData->user[0]->user_type;
            $authorInformation->ciencia_vitae = "";

            // creates one author to the user
            $authorInformation->save();
        } else if ($user->email != $userData->user[0]->email) {
            $user->email = $userData->user[0]->email;

            $user->save();
        }

        $userToSaveInCache = (isset($user)) ? $user : $authorInformation;

        Session(['user' => $userToSaveInCache, 'user_token' => $userData->user_token]);
        
        return redirect()->route('authors.show', ['author' => $user->id]);
    }

    public function logout()
    {
        // Limpa o user da sessão e redireciona para a homepage
        session()->forget('user');

        session()->forget('user_token');

        return redirect('/');
    }
}
