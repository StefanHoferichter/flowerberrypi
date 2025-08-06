<?php

namespace App\Jobs;

use App\Models\Sensor;
use App\Models\SensorJob;
use App\Models\SensorValue;
use App\Services\SensorReader;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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
        Log::info('start handling sensor job');
        
        $job = new SensorJob();
        $job->save();
        
        $sensors = Sensor::where('sensor_type', '4')->get();
        $reader = new SensorReader();
        $readings = $reader->read_temperatures($sensors);
        
        foreach($readings as $reading)
        {
            $v = new SensorValue();
            $v->job_id=$job->id;
            $v->type=1;
            $v->value=$reading->temperature;
            $v->sensor_id=$reading->sensor_id;
            $v->save();
            
            $v = new SensorValue();
            $v->job_id=$job->id;
            $v->type=2;
            $v->value=$reading->humidity;
            $v->sensor_id=$reading->sensor_id;
            $v->save();
        }
        Log::info('finished handling temperatures');
        
        $sensors = Sensor::where('sensor_type', '5')->get();
        $reader = new SensorReader();
        $readings = $reader->read_distances($sensors);

        foreach($readings as $reading)
        {
            $v = new SensorValue();
            $v->job_id=$job->id;
            $v->type=3;
            $v->value=$reading->value;
            $v->sensor_id=$reading->sensor_id;
            $v->save();
        }
        Log::info('finished handling distances');
        
        $sensors = Sensor::where('sensor_type', '6')->get();
        $reader = new SensorReader();
        $readings = $reader->read_humidities($sensors);

        foreach($readings as $reading)
        {
            $v = new SensorValue();
            $v->job_id=$job->id;
            $v->type=4;
            $v->value=$reading->value;
            $v->sensor_id=$reading->sensor_id;
            $v->save();
        }
        Log::info('finished handling humidities');
        
        $cameras = Sensor::where('sensor_type', '7')->get();
        $reader = new SensorReader();
        $pictures = $reader->read_camera($cameras);

        foreach($pictures as $picture)
        {
            $picture->job_id=$job->id;
            $picture->save();
        }
        Log::info('finished handling pictures');
        
        Log::info('finished handling sensor job');
    }
}
