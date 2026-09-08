<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && !$user->is_active) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['email' => __('A sua conta ainda esta pendente de aprovacao.')]);
        }

        return $next($request);
    }
}
