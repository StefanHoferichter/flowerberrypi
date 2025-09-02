<?php

namespace App\Services;

use App\Helpers\DBLock;
use App\Helpers\GlobalStuff;
use App\Models\PercentageConversion;
use App\Models\Picture;
use App\Models\SensorResult;
use App\Models\SensorValue;
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
            $succ = false;
            while (! $succ)
            {
                Log::info('start reading temperatures ' . $sensor->gpio_in);
                $output = null;
                while ($output === null)
                {
                    $output = DBLock::run('sensor_'. $sensor->id, 10, function () use ($sensor)
                    {
                        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_read_temp.py '. $sensor->gpio_in);
                        return $output;
                    });
                    
                    if ($output === null)
                        sleep(1);
                }
                Log::info('finished reading temperatures ' . $output);
                
                if (strpos($output, 'Fehler') !== false) 
                {
                    Log::info('error reading temperatures ' . $output);
                    sleep(2);
                } 
                else 
                {
                    $succ =true;
                    list($temp, $hum) = explode(",", trim($output));
                    $newReading = new TemperatureSensorResult();
                    $newReading->temperature=(float)$temp;
                    $newReading->humidity=(float)$hum;
                    $newReading->name=$sensor->name;
                    $newReading->sensor_id=$sensor->id;
                    $newReading->zone_id=$sensor->zone_id;
                    $newReading->zone_name=$sensor->zone->name;
                    $classification = GlobalStuff::get_classification_from_temperature($newReading->temperature);
                    $newReading->classification=$classification;
                                
                    array_push($readings, $newReading);
                }
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
                $conv = PercentageConversion::where('sensor_id', $sensor->id)->first();
                
                Log::info('start reading distances ' . $sensor->gpio_out . ' ' . $sensor->gpio_in );
                $output = null;
                while ($output === null)
                {
                    $output = DBLock::run('sensor_'. $sensor->id, 10, function () use ($sensor)
                    {
                        $output = shell_exec('sudo /usr/bin/python3 /var/www/html/flowerberrypi/app/python/php_read_distance.py '. $sensor->gpio_out . ' ' . $sensor->gpio_in . ' 20 2>&1');
                        return $output;
                    });
                    
                    if ($output === null)
                        sleep(1);
                }
                Log::info('finished reading distance ' . $output);
                
                if (strpos($output, 'Fehler') !== false) {
                    //                echo "Fehler beim Auslesen des DHT11-Sensors.";
                } else {
                    list($v0) = explode(",", trim($output));
                    $current_value=(float)$v0;
                    Log::info('raw value ' . $current_value);
                    $perc = ($current_value-$conv->lower_limit)/($conv->upper_limit-$conv->lower_limit)*100.0;
                    if ($conv->invert > 0)
                        $perc = 100.0 - $perc;
                    Log::info('converted value ' . $perc);
                    $value = $this->calculate_moving_average($perc, $sensor);
                    $value = round($value, 1);
                    Log::info('moving average value ' . $value);
                    
                    $newReading = new SensorResult();
                    $newReading->value=$value;
                    $newReading->name=$sensor->name;
                    $newReading->sensor_id=$sensor->id;
                    $newReading->zone_id=$sensor->zone_id;
                    $newReading->zone_name=$sensor->zone->name;
                    $newReading->classification=GlobalStuff::get_classification_from_tank($newReading->value);
                                            
                    array_push($readings, $newReading);
                }
            }
        }
        
        return $readings;
    }

    private function calculate_moving_average($current_value, $sensor)
    {
        $vector = [];
        $hist = SensorValue::where('sensor_id', $sensor->id)->latest()->take(9)->get();
        foreach($hist as $h)
        {
            array_push($vector, $h->value);
        }
        array_push($vector, $current_value);
        $ma = round (array_sum($vector) / count($vector), 1);
        
        return $ma;
    }
    
    public function read_i2c_bus()
    {
        Log::info('start reading i2c bus');
        $output = null;
        while ($output === null)
        {
            $output = DBLock::run('i2cdetect', 10, function ()
            {
                $output = shell_exec('sudo i2cdetect -y 1 2>&1');
                return $output;
            });
            
            if ($output === null)
                sleep(1);
        }
        Log::info('finished reading i2c bus ' . $output);
        
        if (strpos($output, 'Fehler') !== false) {
            //                echo "Fehler beim Auslesen des DHT11-Sensors.";
        } else {
   //         echo "Output: {$output}<br>";
        }
        
        $t = [];
        $lines = explode("\n", trim($output));
        
        // erste Zeile: Spaltenüberschriften
        $header = array_map('trim', preg_split('/\s+/', array_shift($lines)));
        
        $i=1;
        $t[0][0] = 'x';
        // HTML-Tabelle starten
        foreach ($header as $h) 
        {
            $t[0][$i] = $h;
            $i++;
        }
        
        $j=1;
        foreach ($lines as $line) 
        {
            $parts = array_map('trim', preg_split('/\s+/', $line));
            $i=0;
            foreach ($parts as $cell) 
            {
                $t[$j][$i] = $cell;
                $i++;
            }
            $j++;
        }
        
        return $t;
    }
    
    public function read_soil_moistures(Collection $sensors)
    {
        $readings = [];
        
        foreach ($sensors as $sensor)
        {
            if ($sensor->enabled > 0)
            {
                usleep(500000); //halbe sekunde
                $conv = PercentageConversion::where('sensor_id', $sensor->id)->first();
                
                Log::info('start reading moisture ' . $sensor->gpio_extra . ' ' . $sensor->gpio_in );
                $output = null;
                while ($output === null)
                {
                    $output = DBLock::run('sensor_'. $sensor->id, 10, function () use ($sensor)
                    {
                        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_read_soil_moisture.py '. $sensor->gpio_extra . ' ' . $sensor->gpio_in . ' 2>&1');
                        return $output;
                    });
                    
                    if ($output === null)
                        sleep(1);
                }
                Log::info('finished reading moisture ' . $output);
                
                if (strpos($output, 'Fehler') !== false) {
                    //                echo "Fehler beim Auslesen des DHT11-Sensors.";
                } else {
                    list($v0) = explode(",", trim($output));
                    Log::info('raw value ' . $v0);
                    //                echo "Entfernung 0: {$v0}<br>";
                    $perc = ($v0-$conv->lower_limit)/($conv->upper_limit-$conv->lower_limit)*100.0;
                    if ($conv->invert > 0)
                        $perc = 100.0 - $perc;
//                        echo "abs {$v0} in % {$perc}<br>";
                    $perc = round($perc, 1);
                    Log::info('converted value ' . $perc);
                    $newReading = new SensorResult();
                    $newReading->value=$perc;
                    $newReading->name=$sensor->name;
                    $newReading->sensor_id=$sensor->id;
                    $newReading->zone_id=$sensor->zone_id;
                    $newReading->zone_name=$sensor->zone->name;
                    $classification = GlobalStuff::get_classification_from_soil_moisture($newReading->value);
                    $newReading->classification=$classification;
                    
                    array_push($readings, $newReading);
                }
            }
        }
        
        return $readings;
    }
    

    public function read_camera(Collection $sensors)
    {
        $readings = [];
        
//        echo 1;
        
        foreach ($sensors as $sensor)
        {
//            echo 2;
            if ($sensor->enabled > 0)
            {
//                echo 3;
                $filename = 'pic_' . date('Y-m-d_H-i-s') . '.jpg';
//                echo $filename;
                $output = null;
                while ($output === null)
                {
                    $output = DBLock::run('sensor_'. $sensor->id, 10, function () use ($filename)
                    {
                        $output = shell_exec("rpicam-jpeg -o /var/www/html/flowerberrypi/public/" . $filename);
                        $output = $filename;
                        return $output;
                    });
                    
                    if ($output === null)
                        sleep(1);
                }
                
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