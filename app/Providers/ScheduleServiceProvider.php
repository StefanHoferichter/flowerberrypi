<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $schedule = app(Schedule::class);
        
        // Hole die Closure aus routes/console.php und führe sie aus
        $scheduleClosure = require base_path('routes/console.php');
        $scheduleClosure($schedule);
    }
}
