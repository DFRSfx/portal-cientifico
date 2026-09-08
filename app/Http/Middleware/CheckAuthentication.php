<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        
        View::share('logedUser', session("user"));

        // Valida se o user está na sessão (significa que fez login com su\)
        if (!$request->session()->has('user')) 
        {
            // Return an authentication error response
            return redirect()->route('user.login');
        }

        return $next($request);
    }
}
