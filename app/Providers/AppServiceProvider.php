<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    
    public function boot(): void
    {
        // Hostname für Views teilen
        $hostname = gethostname();
        View::share('hostname', $hostname);
        
        // Pfad für die Flagfile
        $flagPath = storage_path('app/startup_job_ran');
        
        try {
            // DB prüfen (optional, falls Job DB-Zugriff braucht)
            DB::connection()->getPdo();
            
            // StartupJob nur ausführen, wenn Flagfile nicht existiert
            if (!file_exists($flagPath)) {
                dispatch(new \App\Jobs\StartupJob());
                
                // Flagfile erstellen
                file_put_contents($flagPath, now()->toDateTimeString());
                
                Log::info('StartupJob dispatched successfully (flagfile erstellt)');
            }
        } catch (\Throwable $e) {
            // DB nicht ready → Job überspringen
            Log::warning('StartupJob skipped: DB not ready (' . $e->getMessage() . ')');
        }
    }
}

