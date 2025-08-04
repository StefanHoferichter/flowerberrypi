<?php

namespace App\Jobs;

use App\Models\Sensor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessSensorReadings implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sensors = Sensor::where('sensor_type', '4')->get();
        
        $readings = $this->read_temperatures($sensors);
        
    }
}
