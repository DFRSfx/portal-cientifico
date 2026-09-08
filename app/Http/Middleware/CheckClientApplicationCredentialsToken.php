<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckClientApplicationCredentialsToken
{
     /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next): Response
     {

        // Verifica se o token existe na cache e se é válido
        if (!Cache::has('client_credentials_token')) 
        {
            $response = Http::asForm()->post('http://00-middleware.islagaia.pt/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_SECRET'),
                'scope' => '',
            ]);
        
            if ($response->successful()) 
            {
                $tokenData = $response->json();
                $expirySeconds = $tokenData['expires_in'];

                // Guarda o token na cache
                Cache::put('client_credentials_token', $tokenData['access_token'], $expirySeconds);
            }
        }

        // Adiciona o token no cabeçalho do request

        //$request->headers->add(['Authorization' => 'Bearer ' . Cache::get('client_credentials_token')]);

        return $next($request);
    }
}