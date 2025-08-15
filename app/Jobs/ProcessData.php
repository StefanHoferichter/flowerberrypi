<?php

namespace App\Jobs;

use App\Models\Cycle;
use App\Models\Picture;
use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\SensorJob;
use App\Models\SensorValue;
use App\Models\WateringDecision;
use App\Models\WeatherForecast;
use App\Services\ForecastReader;
use App\Services\SensorReader;
use App\Services\WateringController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessData implements ShouldQueue
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
        Log::info('######### start handling hourly job');
        
        $job = new SensorJob();
        $job->save();

        $hour = date('G');
        $day = date('Y-m-d');
        //       $hour = 8;
        Log::info('date is ' . $day . ' hour of the day is ' . $hour);

        $tod = 0;
        if ($hour > 7 and $hour < 12)
            $tod=1;
        if ($hour >=12 and $hour < 17)
            $tod=2;
        if ($hour >=17)
            $tod=3;
        Log::info('time of the day is ' . $tod);
                
        $this->handle_temperature_sensors($job);
        
        $this->handle_distance_sensors($job);
        
        $this->handle_humidity_sensors($job);
        
        $this->handle_cameras($job, $tod, $day);
                
        $this->handle_weather_forecast($job, $hour);
        
        $this->make_watering_decisions($job, $tod, $day);
        
        $this->execute_watering_decisions($job, $tod, $day);
        
        Log::info('######### finished handling hourly job');
    }
    
    
    private function handle_temperature_sensors($job)
    {
        Log::info('start handling temperatures');
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
            $v->classification=$reading->classification;
            $v->save();
            
            $v = new SensorValue();
            $v->job_id=$job->id;
            $v->type=2;
            $v->value=$reading->humidity;
            $v->sensor_id=$reading->sensor_id;
            $v->classification=0;
            $v->save();
        }
        Log::info('finished handling temperatures');
        
    }
    
    private function handle_distance_sensors($job)
    {
        Log::info('start handling distances');
        
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
            $v->classification=0;
            $v->save();
        }
        Log::info('finished handling distances');
    }
    
    private function handle_humidity_sensors($job)
    {
        Log::info('start handling humidities');
        
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
            $v->classification=$reading->classification;
            $v->save();
        }
        Log::info('finished handling humidities');
    }
    
    private function handle_cameras($job, $tod, $day)
    {
        Log::info('start handling pictures');
        
        $exists = Picture::where('day', $day)->where('tod', $tod)->exists();
        
        if (!$exists)
        {
            $cameras = Sensor::where('sensor_type', '7')->get();
            $reader = new SensorReader();
            $pictures = $reader->read_camera($cameras);
            
            foreach($pictures as $picture)
            {
                $picture->job_id=$job->id;
                $picture->day=$day;
                $picture->tod=$tod;
                $picture->save();
                Log::info('made picture ' . $picture->filename);
            }
        }
        Log::info('finished handling pictures');
    }
    
    private function handle_weather_forecast($job, $hour)
    {
        Log::info('start handling weather forecasts');
        if ($hour > 7)
        {
            $reader = new ForecastReader();
            $wf = $reader->read_daily_api();
            $exists = WeatherForecast::where('day', $wf->day)->exists();
            if (!$exists)
                $wf->save();
        }
        Log::info('finished handling weather forecasts');
    }
    
    
    private function make_watering_decisions($job, $tod, $day)
    {
        Log::info('start making watering decisions');
        if ($tod > 0)
        {
            $tempSensor = SensorValue::where('type', '1')->where('job_id', $job->id)->first();
            $wf = WeatherForecast::where('day', $day)->first();
            Log::info('outdoor temp classification ' . $wf->classification . ' indoor temp classification ' . $tempSensor->classification);
            
            $exists = WateringDecision::where('day', $day)->where('tod', $tod)->exists();
            if (!$exists)
            {
                $cycles = Cycle::where('enabled', 1)->where('has_watering', 1)->get();
                foreach($cycles as $cycle)
                {
                    Log::info('analyzing cycle ' . $cycle->name);
                    $sensors = Sensor::where('cycle_id', $cycle->id)->get();
                    $max_humidity_classification = 0;
                    foreach($sensors as $sensor)
                    {
                        Log::info('analyzing sensor ' . $sensor->name . ' ' .  $sensor->sensor_type);
                        if ($sensor->sensor_type == 6 and $sensor->enabled)
                        {
                            Log::info('analyzing sensor ' . $sensor->id . ' ' . $job->id);
                            $v = SensorValue::where('sensor_id', $sensor->id)->where('job_id', $job->id)->first();
                            Log::info('humidity classification ' . $v->classification);
                            
                            if ($max_humidity_classification < $v->classification)
                                $max_humidity_classification = $v->classification;
                        }
                    }
                    $wd = new WateringDecision();
                    $wd->cycle_id=$cycle->id;
                    $wd->humidity_classification=$max_humidity_classification;
                    if ($cycle->outdoor)
                        $wd->forecast_classification=$wf->classification;
                    else
                        $wd->forecast_classification=$tempSensor->classification;
                    $wd->day=date('Y-m-d');
                    $wd->tod=$tod;
                    $wd->watering_classification=($wd->humidity_classification + $wd->forecast_classification) /2 ;
                    $wd->save();
                    Log::info('watering decision for cycle ' . $wd->cycle_id . ' is ' . $wd->watering_classification);
                }
            }
        }
        Log::info('finished making watering decisions');
    }
     
    private function execute_watering_decisions($job, $tod, $day)
    {
        Log::info('start executing watering decisions');
        
        if ($tod > 0)
        {
            Log::info('start executing watering');
            $decisions = WateringDecision::where('day', $day)->where('tod', $tod)->where('executed', 0)->get();
            foreach($decisions as $decision)
            {
                Log::info('executing decision ' . $decision->cycle_id . ' watering ' . $decision->watering_classification);
                
//                $decision->watering_classification = 3;
                
                $sensor = Sensor::where('sensor_type', '1')->first();
                $remoteSocket = RemoteSocket::where('cycle_id', $decision->cycle_id)->first();
                if ($remoteSocket != null)
                {
                    $this->water_via_remote_socket($decision->watering_classification, $sensor, $remoteSocket);
                }
                $relay = Sensor::where('sensor_type', '3')->where('cycle_id', $decision->cycle_id)->first();
                if ($relay != null)
                {
                    $this->water_via_realy($decision->watering_classification, $relay);
                }
                
                $decision->executed=1;
                $decision->save();
            }
        }
        Log::info('finished executing watering decisions');
        
    }
    
    private function water_via_remote_socket($classification, $sensor, $remoteSocket)
    {
        Log::info('start watering with remote socket ' . $remoteSocket->name . ' classification ' . $classification);
        
        $controller = new WateringController();
        
        $loops=0;
        if ($classification==1)
        {
            $loops=1;
            $sleep=1;
        }
        if ($classification==2)
        {
            $loops=1;
            $sleep=5;
        }
        if ($classification==3)
        {
            $loops=2;
            $sleep=5;
        }
        
        for ($i = 0; $i < $loops; $i++)
        {
            Log::info('switching  on remote socket ' . $remoteSocket->name);
            $controller->control_remote_socket($sensor->gpio_out, $remoteSocket->code_on);
            sleep($sleep);
            $controller->control_remote_socket($sensor->gpio_out, $remoteSocket->code_off);
            Log::info('switching off remote socket ' . $remoteSocket->name);
        }

        Log::info('finished watering with remote socket ' . $remoteSocket->name);
    }
 
    private function water_via_realy($classification, $relay)
    {
        Log::info('start watering with relay ' . $relay->name . ' classification ' . $classification);

        if ($classification==1)
        {
            $sleep=1;
        }
        if ($classification==2)
        {
            $sleep=5;
        }
        if ($classification==3)
        {
            $sleep=10;
        }
        
        $controller = new WateringController();
        Log::info('switching on relay ' . $relay->name);
        $controller->control_relay($relay->gpio_out, 0);
        sleep($sleep);
        $controller->control_relay($relay->gpio_out, 1);
        Log::info('switching off relay ' . $relay->name);

        Log::info('finished watering with relay ' . $relay->name . ' classification ' . $classification);
    }
}
