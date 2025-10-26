<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        // Hostname ermitteln (System)
        $hostname = gethostname(); // oder php_uname('n')
        
        // Mit allen Blade-Views teilen
        View::share('hostname', $hostname);
    }
}
