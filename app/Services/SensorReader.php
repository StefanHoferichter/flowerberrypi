<?php

namespace App\Services;

use App\Models\SensorResult;
use App\Models\Picture;
use App\Models\TemperatureSensorResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;


class SensorReader
{
    public function read_temperatures(Collection $sensors)
    {
        $readings = [];
        
        foreach ($sensors as $sensor)
        {
            Log::info('start reading temperatures ' . $sensor->gpio_in);
            $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_read_temp.py '. $sensor->gpio_in);
            Log::info('finished reading temperatures ' . $output);
            
            if (strpos($output, 'Fehler') !== false) {
                //                echo "Fehler beim Auslesen des DHT11-Sensors.";
            } else {
                list($temp, $hum) = explode(",", trim($output));
                //                echo "Temperatur: {$temp} °C<br>";
                //                echo "Luftfeuchtigkeit: {$hum} %<br>";
                $newReading = new TemperatureSensorResult();
                $newReading->temperature=(float)$temp;
                $newReading->humidity=(float)$hum;
                $newReading->name=$sensor->name;
                $newReading->sensor_id=$sensor->id;
                
                if ($temp > 24)
                    $newReading->classification=3;
                else if ($temp > 15)
                    $newReading->classification=2;
                else        
                    $newReading->classification=1;
                            
                array_push($readings, $newReading);
            }
            
        }
        
        return $readings;
    }
    
    
    public function read_distances(Collection $sensors)
    {
        $readings = [];
        
        foreach ($sensors as $sensor)
        {
            if ($sensor->enabled > 0)
            {
                Log::info('start reading distances ' . $sensor->gpio_out . ' ' . $sensor->gpio_in );
                $output = shell_exec('sudo /usr/bin/python3 /var/www/html/flowerberrypi/app/python/php_read_distance.py '. $sensor->gpio_out . ' ' . $sensor->gpio_in . ' 20 2>&1');
                Log::info('finished reading distance ' . $output);
                
                if (strpos($output, 'Fehler') !== false) {
                    //                echo "Fehler beim Auslesen des DHT11-Sensors.";
                } else {
                    list($v0) = explode(",", trim($output));
                           echo "Entfernung 0: {$v0}<br>";
                    $newReading = new SensorResult();
                    $newReading->value=(float)$v0;
                    $newReading->name=$sensor->name;
                    $newReading->sensor_id=$sensor->id;
                    
                    array_push($readings, $newReading);
                }
            }
        }
        
        return $readings;
    }
    
    public function read_humidities(Collection $sensors)
    {
        $readings = [];
        
        foreach ($sensors as $sensor)
        {
            if ($sensor->enabled > 0)
            {
                usleep(500000); //halbe sekunde
                Log::info('start reading moisture ' . $sensor->gpio_extra . ' ' . $sensor->gpio_in );
                $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_read_humidity.py '. $sensor->gpio_extra . ' ' . $sensor->gpio_in . ' 2>&1');
                Log::info('finished reading moisture ' . $output);
                
                if (strpos($output, 'Fehler') !== false) {
                    //                echo "Fehler beim Auslesen des DHT11-Sensors.";
                } else {
                    list($v0) = explode(",", trim($output));
                    //                echo "Entfernung 0: {$v0}<br>";
                    $newReading = new SensorResult();
                    $newReading->value=$v0;
                    $newReading->name=$sensor->name;
                    $newReading->sensor_id=$sensor->id;
                    $newReading->zone_id=$sensor->zone_id;
                    
//                    $newReading->value = 1.8;
                        
                    if ($newReading->value < 1.7)
                        $newReading->classification=1;
                    else if ($newReading->value > 2.3)
                        $newReading->classification=3;
                     else
                         $newReading->classification=2;
                         
                    
                    array_push($readings, $newReading);
                }
            }
        }
        
        return $readings;
    }
    

    public function read_camera(Collection $sensors)
    {
        $readings = [];
        
        echo 1;
        
        foreach ($sensors as $sensor)
        {
            echo 2;
            if ($sensor->enabled > 0)
            {
                echo 3;
                $filename = 'pic_' . date('Y-m-d_H-i-s') . '.jpg';
                echo $filename;
                $output = shell_exec("rpicam-jpeg -o /var/www/html/flowerberrypi/public/" . $filename);
                echo $output;

                $newReading = new Picture();
                $newReading->type=5;
                $newReading->filename=$filename;
                //                $newReading->name=$sensor->name;
                $newReading->sensor_id=$sensor->id;
                
                array_push($readings, $newReading);
                
            }
        }
        return $readings;
    }
}