<?php

namespace App\Jobs;

use App\Helpers\GlobalStuff;
use App\Models\HourlyWeatherForecast;
use App\Models\Picture;
use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\SensorJob;
use App\Models\SensorValue;
use App\Models\WateringDecision;
use App\Models\WeatherForecast;
use App\Models\WiFiSocket;
use App\Models\Zone;
use App\Services\ForecastReader;
use App\Services\MQTTController;
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
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('######### start handling hourly job');
        
        $job = new SensorJob();
        $job->status = "S";
        $job->save();

        $hour = date('G');
        $day = date('Y-m-d');
        //       $hour = 8;
        Log::info('date is ' . $day . ' hour of the day is ' . $hour);

        $tod=GlobalStuff::get_tod_from_hour($hour);
        Log::info('time of the day is ' . $tod);
                
        $this->handle_temperature_sensors($job);
        
        $this->handle_distance_sensors($job);
        
        $this->handle_moisture_sensors($job);
        
        $this->handle_cameras($job, $tod, $day);
                
        $this->handle_weather_forecast($job, $hour);
        
        $this->make_watering_decisions($job, $tod, $day, $hour);
        
        $this->execute_watering_decisions($job, $tod, $day);
        
        $this->publish_mqtt_to_ha();

//        self::clear_flagfile();
        
        $job->status = "E";
        $job->save();
        
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
            $v->hour = date('G');
            $v->day = date('Y-m-d');
            $v->classification=$reading->classification;
            $exists = SensorValue::where('day', $v->day)->where('hour', $v->hour)->where('type', $v->type)->where('sensor_id', $v->sensor_id)->exists();
            if (!$exists)
                $v->save();
                
            $v = new SensorValue();
            $v->job_id=$job->id;
            $v->type=2;
            $v->value=$reading->humidity;
            $v->sensor_id=$reading->sensor_id;
            $v->hour = date('G');
            $v->day = date('Y-m-d');
            $v->classification=0;
            $exists = SensorValue::where('day', $v->day)->where('hour', $v->hour)->where('type', $v->type)->where('sensor_id', $v->sensor_id)->exists();
            if (!$exists)
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
            $v->hour = date('G');
            $v->day = date('Y-m-d');
            $v->classification=$reading->classification;
            $exists = SensorValue::where('day', $v->day)->where('hour', $v->hour)->where('type', $v->type)->where('sensor_id', $v->sensor_id)->exists();
            if (!$exists)
                $v->save();
        }
        Log::info('finished handling distances');
    }
    
    private function handle_moisture_sensors($job)
    {
        Log::info('start handling soil moistures');
        
        $sensors = Sensor::where('sensor_type', '6')->get();
        $reader = new SensorReader();
        $readings = $reader->read_soil_moistures($sensors);
        
        foreach($readings as $reading)
        {
            $v = new SensorValue();
            $v->job_id=$job->id;
            $v->type=4;
            $v->value=$reading->value;
            $v->sensor_id=$reading->sensor_id;
            $v->hour = date('G');
            $v->day = date('Y-m-d');
            $v->classification=$reading->classification;
            $exists = SensorValue::where('day', $v->day)->where('hour', $v->hour)->where('type', $v->type)->where('sensor_id', $v->sensor_id)->exists();
            if (!$exists)
                $v->save();
        }
        Log::info('finished handling soil moistures');
    }
    
    private function handle_cameras($job, $tod, $day)
    {
        Log::info('start handling pictures');
        
        $exists = Picture::where('day', $day)->where('tod', $tod)->exists();
        
        if (!$exists and $tod > 0)
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
        if ($hour >= 0)
        {
            $reader = new ForecastReader();
            $wf = $reader->read_daily_api();
            $exists = WeatherForecast::where('day', $wf->day)->exists();
            if (!$exists)
                $wf->save();
            
            $hwf = $reader->read_hourly_api();
            $exists = HourlyWeatherForecast::where('day', $hwf[0]->day)->exists();
            if (!$exists)
                foreach ($hwf as $h) 
                {
                    $h->save();
                }
        }
        Log::info('finished handling weather forecasts');
    }
    
    
    private function make_watering_decisions($job, $tod, $day, $hour)
    {
        Log::info('start making watering decisions');
        if ($tod > 0)
        {
            $max_temp_classification = 0;
            $tempSensor = SensorValue::where('type', '1')->where('day', $day)->where('hour', $hour)->first();
            if ($tempSensor != null)
            {
                Log::info(' indoor temp classification ' . $tempSensor->classification);
                $max_temp_classification = $tempSensor->classification;
            }
            $wf = WeatherForecast::where('day', $day)->first();
            if ($wf != null)
            {
                Log::info('outdoor temp classification ' . $wf->classification);
                $max_forecast_classification = $wf->classification;
                
                $zones = Zone::where('enabled', 1)->where('has_watering', 1)->get();
                foreach($zones as $zone)
                {
                    Log::info('analyzing zone ' . $zone->name);
                    $exists = WateringDecision::where('day', $day)->where('tod', $tod)->where('zone_id', $zone->id)->exists();
                    if (!$exists)
                    {
                        Log::info('no watering decision found for ' . $zone->name . ' tod ' .  $tod);
                        $sensors = Sensor::where('zone_id', $zone->id)->get();
                        $max_moisture_classification = 0;
                        $max_tank_classification = 0;
                        foreach($sensors as $sensor)
                        {
                            Log::info('analyzing sensor ' . $sensor->name . ' ' .  $sensor->sensor_type);
                            if ($sensor->sensor_type == 6 and $sensor->enabled)
                            {
                                Log::info('analyzing sensor ' . $sensor->id . ' hour ' . $hour);
                                $v = SensorValue::where('sensor_id', $sensor->id)->where('day', $day)->where('hour', $hour)->first();
                                Log::info('moisture classification ' . $v->classification);
                                
                                if ($max_moisture_classification < $v->classification)
                                    $max_moisture_classification = $v->classification;
                            }
                            if ($sensor->sensor_type == 5 and $sensor->enabled)
                            {
                                Log::info('analyzing sensor ' . $sensor->id . ' hour ' . $hour);
                                $v = SensorValue::where('sensor_id', $sensor->id)->where('day', $day)->where('hour', $hour)->first();
                                Log::info('tank classification ' . $v->classification);
                                
                                if ($max_tank_classification < $v->classification)
                                    $max_tank_classification = $v->classification;
                            }
                        }

                        $controller = new WateringController();
                        $wd = $controller->make_watering_decision($zone, $max_moisture_classification, $max_tank_classification, $max_temp_classification, $max_forecast_classification);
                        $wd->tod=$tod;
                        $wd->job_id=$job->id;
                        $wd->save();
                        Log::info('watering decision for zone ' . $wd->zone_id . ' is ' . $wd->watering_classification);
                    }
                    else 
                        Log::info('watering decision found for ' . $zone->name . ' tod ' .  $tod);
                }
            }
        }
        Log::info('finished making watering decisions');
    }
     
    private function execute_watering_decisions($job, $tod, $day)
    {
        Log::info('start executing watering decisions');
        $mqttcontroller = new MQTTController();
        
        if ($tod > 0)
        {
            Log::info('start executing watering');
            $decisions = WateringDecision::where('day', $day)->where('tod', $tod)->where('executed', 0)->get();
            foreach($decisions as $decision)
            {
                Log::info('executing decision ' . $decision->zone_id . ' watering ' . $decision->watering_classification);
                
//                $decision->watering_classification = 3;
                
                $sensor = Sensor::where('sensor_type', '1')->first();
                $remoteSocket = RemoteSocket::where('zone_id', $decision->zone_id)->first();
                if ($remoteSocket != null)
                {
                    self::water_via_433mhz_socket($decision->watering_classification, $sensor, $remoteSocket);
                }
                $wifiSocket = WiFiSocket::where('zone_id', $decision->zone_id)->first();
                if ($wifiSocket != null)
                {
                    self::water_via_wifi_socket($decision->watering_classification, $wifiSocket);
                }
                $relay = Sensor::where('sensor_type', '3')->where('zone_id', $decision->zone_id)->first();
                if ($relay != null)
                {
                    self::water_via_relay($decision->watering_classification, $relay);
                }
                
                $mqttcontroller->send_ha_watering($decision);
                
                
                $decision->executed=1;
                $decision->save();
            }
        }
        Log::info('finished executing watering decisions');
        
    }
    
    private function publish_mqtt_to_ha()
    {
        $controller = new MQTTController();
        $controller->send_discovery_messages();
    }
    
    
    public static function water_via_433mhz_socket($classification, $sensor, $remoteSocket)
    {
        Log::info('start watering with 433mhz socket ' . $remoteSocket->name . ' classification ' . $classification);
        
        $controller = new WateringController();
        $mqttcontroller = new MQTTController();
        
        $loops=0;
        if ($classification==1)
        {
            $loops=0;
            $sleep=2;
        }
        if ($classification==2)
        {
            $loops=1;
            $sleep=60;
        }
        if ($classification==3)
        {
            $loops=2;
            $sleep=60;
        }

        $wt = $loops*$sleep;
        Log::info('watering time ' . $wt);
        
        for ($i = 0; $i < $loops; $i++)
        {
            Log::info('switching  off 433mhz socket ' . $remoteSocket->name . ' as stability measure');
            $controller->control_433mhz_socket($sensor->gpio_out, $remoteSocket->code_off);
            $mqttcontroller->send_status_message("433mhz_socket", $remoteSocket->id, "OFF");
            sleep(2);
            Log::info('switching  on 433mhz socket ' . $remoteSocket->name);
            $controller->control_433mhz_socket($sensor->gpio_out, $remoteSocket->code_on);
            $mqttcontroller->send_status_message("433mhz_socket", $remoteSocket->id, "ON");
            sleep($sleep);
            $controller->control_433mhz_socket($sensor->gpio_out, $remoteSocket->code_off);
            $mqttcontroller->send_status_message("433mhz_socket", $remoteSocket->id, "OFF");
            Log::info('switching off 433mhz socket ' . $remoteSocket->name);
            sleep(2);
        }

        Log::info('finished watering with 433mhz socket ' . $remoteSocket->name);
    }

    public static function water_via_wifi_socket($classification, $wifiSocket)
    {
        Log::info('start watering with wifi socket ' . $wifiSocket->name . ' classification ' . $classification);
        
        $controller = new WateringController();
        $mqttcontroller = new MQTTController();
        
        $loops=0;
        if ($classification==1)
        {
            $loops=0;
            $sleep=2;
        }
        if ($classification==2)
        {
            $loops=1;
            $sleep=60;
        }
        if ($classification==3)
        {
            $loops=2;
            $sleep=60;
        }
        
        $wt = $loops*$sleep;
        Log::info('watering time ' . $wt);
        
        for ($i = 0; $i < $loops; $i++)
        {
            Log::info('switching  off wifi socket ' . $wifiSocket->name . ' as stability measure');
            $controller->control_wifi_socket($wifiSocket->url_off);
            $mqttcontroller->send_status_message("wifi_socket", $wifiSocket->id, "OFF");
            sleep(2);
            Log::info('switching  on wifi socket ' . $wifiSocket->name);
            $controller->control_wifi_socket($wifiSocket->url_on);
            $mqttcontroller->send_status_message("wifi_socket", $wifiSocket->id, "ON");
            sleep($sleep);
            $controller->control_wifi_socket($wifiSocket->url_off);
            $mqttcontroller->send_status_message("wifi_socket", $wifiSocket->id, "OFF");
            Log::info('switching off wifi socket ' . $wifiSocket->name);
            sleep(2);
        }
        
        Log::info('finished watering with wifi socket ' . $wifiSocket->name);
    }
    
    public static function water_via_relay($classification, $relay)
    {
        Log::info('start watering with relay ' . $relay->name . ' factor ' . $relay->gpio_extra . ' classification ' . $classification);

        $factor = $relay->gpio_extra;
        if ($factor==0)
            $factor=1;
        
        if ($classification==1)
        {
            $sleep=0;
        }
        if ($classification==2)
        {
            $sleep=5;
        }
        if ($classification==3)
        {
            $sleep=10;
        }
        
        $sleep = $sleep*$factor;
        Log::info('watering time ' . $sleep);
        
        $controller = new WateringController();
        $mqttcontroller = new MQTTController();
        if ($sleep > 0)
        {
            Log::info('switching on relay ' . $relay->name);
            $controller->control_relay($relay->gpio_out, 0);
            $mqttcontroller->send_status_message("relay", $relay->id, "ON");
            sleep($sleep);
            $controller->control_relay($relay->gpio_out, 1);
            $mqttcontroller->send_status_message("relay", $relay->id, "OFF");
            Log::info('switching off relay ' . $relay->name);
        }

        Log::info('finished watering with relay ' . $relay->name . ' classification ' . $classification);
    }
    
    public static function clear_flagfile()
    {
        @unlink(storage_path('app/startup_job_ran'));
        Log::info('flagfile deleted');
    }
}
