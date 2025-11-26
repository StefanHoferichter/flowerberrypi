<?php

namespace App\Services;

use App\Helpers\GlobalStuff;
use App\Models\WateringDecision;
use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class WateringController
{
    /*
    public function control_remote_socket($gpio_out, $code)
    {
        Log::info('start controlling remote socket ' . $code . ' ' . $gpio_out );
        $output = shell_exec('sudo /var/www/html/flowerberrypi/app/python/codesend ' . $code . ' 2>&1');
        Log::info('finished controlling remote socket ' . $code . ' ' . $gpio_out . ' ' . $output );
    }
    */
    public function control_433mhz_socket($gpio_out, $code)
    {
        $isPi5 = GlobalStuff::isRaspberryPi5();
        Log::info('start controlling 433mhz socket ' . $code . ' ' . $gpio_out );
        
        if ($isPi5)
        {
            $output = shell_exec('sudo /var/www/html/flowerberrypi/app/c/send433_pi5 ' . $gpio_out . ' ' .  $code .  ' 2>&1');
            sleep(1);
            $output = shell_exec('sudo /var/www/html/flowerberrypi/app/c/send433_pi5 ' . $gpio_out . ' ' .  $code .  ' 2>&1');
        }
        else
        {
            $output = shell_exec('sudo /usr/bin/python3 /var/www/html/flowerberrypi/app/python/php_send_433mhz_v2.py ' . $gpio_out . ' ' .  $code .  ' 2>&1');
            sleep(1);
            $output = shell_exec('sudo /usr/bin/python3 /var/www/html/flowerberrypi/app/python/php_send_433mhz_v2.py ' . $gpio_out . ' ' .  $code .  ' 2>&1');
        }
        Log::info('finished controlling 433mhz socket ' . $code . ' ' . $gpio_out . ' ' . $output );
    }

    public function control_wifi_socket($url)
    {
        Log::info('start controlling wifi socket ' . $url );
        
        try 
        {
            $response = Http::timeout(3)->throw()->get($url);
            Log::info('Response received', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } 
        catch (\Illuminate\Http\Client\ConnectionException $e) 
        {
            Log::error("Connection error: " . $e->getMessage());
        } 
        catch (\Illuminate\Http\Client\RequestException $e) 
        {
            Log::error("HTTP request failed: " . $e->getMessage(), ['status' => optional($e->response)->status()]);
        } 
        catch (\Exception $e) 
        {
            Log::error("General error controlling WiFi socket: " . $e->getMessage());
        }
        
        Log::info('finished controlling wifi socket ' . $url );
    }
    
    public function control_relay($gpio_out, $code)
    {
        $isPi5 = GlobalStuff::isRaspberryPi5();
        Log::info('start controlling relay ' . $code . ' ' . $gpio_out . ' ' . $isPi5);
        if ($isPi5)
            $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_set_relay_pi5.py '. $gpio_out. ' ' . $code  );
        else
            $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_set_relay.py '. $gpio_out. ' ' . $code  );
            Log::info('finished controlling relay ' . $code . ' ' . $gpio_out . ' ' . $output );
    }
    
    public function sniff($timeout)
    {
        Log::info('start reading 433MHz');
        $cmd = ['sudo', '/var/www/html/flowerberrypi/app/python/RFSniffer'];
        
        $process = new Process($cmd);
        $process->setTimeout($timeout); // optional Timeout
        
        $output = '';
        
        try {
            $process->run();
            
            $output = trim($process->getOutput());
            $errorOutput = trim($process->getErrorOutput());
            
            if (!$process->isSuccessful()) 
            {
                Log::error("RFSniffer failed: $errorOutput");
                return $errorOutput;
            }
            
            return $output;
            
        }
        catch (ProcessTimedOutException $e)
        {
            $output = trim($process->getOutput());
            Log::error('RFSniffer timeout exception: ' . $output);
            return $output;
        }
        catch (Exception $e) 
        {
            Log::error('RFSniffer execution exception: ' . $e->getMessage());
            return $e->getMessage();
        }
        
        Log::info('finished reading 433MHz');
        
    }
    
    public function make_watering_decision($zone, $max_moisture_classification, $max_tank_classification, $max_temp_classification, $max_forecast_classification)
    {
        $wd = new WateringDecision();
        $wd->zone_id=$zone->id;
        $wd->soil_moisture_classification=$max_moisture_classification;
        $wd->tank_classification=$max_tank_classification;
        if ($zone->outdoor)
            $wd->forecast_classification=$max_forecast_classification;
        else
            $wd->forecast_classification=$max_temp_classification;
                
        $wd->day=date('Y-m-d');
        
        if ($wd->soil_moisture_classification == 1)
        {
            Log::info('watering decision for zone ' . $wd->zone_id . ' is 1 because of high moisture classification');
            $wd->watering_classification = 1;
        }
        else
        {
            Log::info('watering decision for zone ' . $wd->zone_id . ' is avg of moisture and temp classification');
            $wd->watering_classification=round(($wd->soil_moisture_classification + $wd->forecast_classification)/2);
        }
        
        if ($wd->tank_classification == 3)
        {
            Log::info('lowering watering decision to 1 for zone ' . $wd->zone_id . ' because of low tank level classification');
            $wd->watering_classification = 1;
        }
        if ($wd->tank_classification == 2 and $wd->watering_classification == 3)
        {
            Log::info('lowering watering decision for zone ' . $wd->zone_id . ' because of medium tank level classification');
            $wd->watering_classification--;
        }
        Log::info('watering decision for zone ' . $wd->zone_id . ' is ' . $wd->watering_classification);
        
        return $wd;
    }
}


