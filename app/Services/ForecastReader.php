<?php

namespace App\Services;

use App\Models\HourlyWeatherForecast;
use App\Models\WeatherForecast;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ForecastReader
{
    public function read_hourly_api()
    {
        $url = "https://api.open-meteo.com/v1/forecast?latitude=52.5244&longitude=13.4105&hourly=temperature_2m,precipitation,cloud_cover&forecast_days=1";
        
        $hwf = [];
        $response = $this->callApi($url);

        if ($response != null)
        {
            $hours = $response['hourly']['time'];
            $precipitation = $response['hourly']['precipitation'];
            $temperatures = $response['hourly']['temperature_2m'];
            $cloudCovers = $response['hourly']['cloud_cover'];
            $i=0;
            foreach($hours as $hour)
            {
                $entry = new HourlyWeatherForecast();
                $entry->day = substr($hours[$i], 0, 10);
                $entry->hour = $i;
                $entry->temperature = $temperatures[$i];
                $entry->precipitation = $precipitation[$i];
                $entry->cloud_cover = $cloudCovers[$i];
                $entry->classification = 0;
                $hwf[] = $entry;
                $i++;
            }
        }
        else
        {
            for ($i = 0; $i <= 23; $i++) 
            {
                $entry = new HourlyWeatherForecast();
                $entry->day = date('Y-m-d');
                $entry->hour = $i;
                $entry->temperature = -1;
                $entry->precipitation = 0;
                $entry->cloud_cover = 0;
                $entry->classification = 0;
                $hwf[] = $entry;
            }
        }
        return $hwf;
    }
    
    
    public function read_daily_api()
    {
        $url = "https://api.open-meteo.com/v1/forecast?latitude=52.5244&longitude=13.4105&daily=temperature_2m_max,temperature_2m_min,sunshine_duration,rain_sum&timezone=Europe%2FBerlin&forecast_days=1";
        $wf = new WeatherForecast();
        
        $response = $this->callApi($url);
        if ($response != null)
        {
            $wf->min_temp = $response['daily']['temperature_2m_min'][0];
            $wf->max_temp = $response['daily']['temperature_2m_max'][0];
            $wf->sunshine_duration = $response['daily']['sunshine_duration'][0];
            $wf->rain_sum = $response['daily']['rain_sum'][0];
            $wf->day = $response['daily']['time'][0];
            
            if ($wf->max_temp > 24)
                $wf->classification=3;
             else if ($wf->rain_sum > 5)
                $wf->classification=1;
             else
                $wf->classification=2;
        }
        {
            $wf->min_temp = -1;
            $wf->max_temp = 1;
            $wf->sunshine_duration = 0;
            $wf->rain_sum = 0;
            $wf->day = date('Y-m-d');
            $wf->classification=0;
        }
        
        return $wf;
    }
    
    public function callApi($url)
    {
        try 
        {
            $response = Http::timeout(10)->get($url);
            
            if ($response->successful()) {
                $data = $response->json();
                return  $data;
            } 
            else 
            {
                Log::error("API call failed ({$response->status()}) for URL: {$url}");
                return null;
            }
        } 
        catch (ConnectionException $e) 
        {
            // 👉 fängt cURL-Fehler wie "Could not resolve host"
            Log::error("Connection error calling API {$url}: " . $e->getMessage());
            return null;
        }
        catch (RequestException $e) 
        {
            // fängt z. B. Timeouts oder 4xx/5xx bei ->throw()
              Log::error("Request error calling API {$url}: " . $e->getMessage());
            return null;
        }
        catch (Exception $e) 
        {
            Log::error('Unexpected error: ' . $e->getMessage());
            return null;
        }
    }
        
}