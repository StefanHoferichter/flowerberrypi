<?php

use App\Jobs\ProcessData;
use App\Models\SensorJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



return function (Schedule $schedule) 
{
    $schedule->job(new ProcessData())->hourly();
};