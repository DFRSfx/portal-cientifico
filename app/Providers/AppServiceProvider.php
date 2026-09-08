<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
    }
}
