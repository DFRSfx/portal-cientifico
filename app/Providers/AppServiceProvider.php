<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $mainPath = database_path('migrations');
        
        $directories = glob($mainPath . '/*', GLOB_ONLYDIR);

        $paths = array_merge([$mainPath], $directories);

        $this->loadMigrationsFrom($paths);

        Paginator::useBootstrapFive();

        View::share('logedUser', session("user"));

        // Share admin counters for navbar badges
        View::composer('components.layouts.nav-bar', function ($view) {
            $pendingApprovalCount = 0;
            $pendingVerificationCount = 0;
            $activeUserCount = 0;

            if (Auth::check() && Auth::user()->type === 'administrative') {
                $pendingApprovalCount = User::where('is_active', 0)->count();
                $pendingVerificationCount = User::whereNull('email_verified_at')->count();
                $activeUserCount = User::where('is_active', 1)->count();
            }

            $view->with([
                'pendingApprovalCount' => $pendingApprovalCount,
                'pendingVerificationCount' => $pendingVerificationCount,
                'activeUserCount' => $activeUserCount,
            ]);
        });
    }
}
