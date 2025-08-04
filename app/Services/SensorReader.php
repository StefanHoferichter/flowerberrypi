<?php

namespace App\Services;

use App\Models\SensorResult;
use App\Models\TemperatureSensorResult;
use Illuminate\Support\Collection;


class SensorReader
{
    public function read_temperatures(Collection $sensors)
    {
        $readings = [];
        
        foreach ($sensors as $sensor)
        {
            $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_read_temp.py '. $sensor->gpio_in);
            
            if (strpos($output, 'Fehler') !== false) {
                //                echo "Fehler beim Auslesen des DHT11-Sensors.";
            } else {
                list($temp, $hum) = explode(",", trim($output));
                //                echo "Temperatur: {$temp} °C<br>";
                //                echo "Luftfeuchtigkeit: {$hum} %<br>";
                $newReading = new TemperatureSensorResult();
                $newReading->temperature=$temp;
                $newReading->humidity=$hum;
                $newReading->name=$sensor->name;
                $newReading->sensor_id=$sensor->id;
                
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
                $output = shell_exec('sudo /usr/bin/python3 /var/www/html/flowerberrypi/app/python/php_read_distance.py '. $sensor->gpio_out . ' ' . $sensor->gpio_in . ' 2>&1');
                //            echo $output;
                
                if (strpos($output, 'Fehler') !== false) {
                    //                echo "Fehler beim Auslesen des DHT11-Sensors.";
                } else {
                    list($v0) = explode(",", trim($output));
                    //                echo "Entfernung 0: {$v0}<br>";
                    $newReading = new SensorResult();
                    $newReading->value=$v0;
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
                $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_read_humidity.py '. $sensor->gpio_extra . ' ' . $sensor->gpio_in . ' 2>&1');
                //            echo $output;
                
                if (strpos($output, 'Fehler') !== false) {
                    //                echo "Fehler beim Auslesen des DHT11-Sensors.";
                } else {
                    list($v0) = explode(",", trim($output));
                    //                echo "Entfernung 0: {$v0}<br>";
                    $newReading = new SensorResult();
                    $newReading->value=$v0;
                    $newReading->name=$sensor->name;
                    $newReading->sensor_id=$sensor->id;
                    
                    array_push($readings, $newReading);
                }
            }
        }
        
        return $readings;
    }
    
    
}
