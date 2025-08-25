<?php

namespace App\Services;

use App\Models\HourlyWeatherForecast;
use App\Models\WeatherForecast;
use Illuminate\Support\Facades\Http;


class ForecastReader
{
    public function read_hourly_api()
    {
        $url = "https://api.open-meteo.com/v1/forecast?latitude=52.5244&longitude=13.4105&hourly=temperature_2m,precipitation,cloud_cover&forecast_days=1";
        
        $hwf = [];
        $response = $this->callApi($url);
        
        //        print_r( $response);
        //        echo "<br>";
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
//            echo $i . " " . $hour . " " . $entry->temperature. " " . $entry->precipitation. " " . $entry->cloud_cover . "<br>";
            $i++;
        }
        foreach($temperatures as $temp)
        {
            //            echo $temp . "<br>";
        }
        foreach($cloudCovers as $cc)
        {
            //            echo $cc . "<br>";
        }
        
 //       print_r($hwf);
        
        return $hwf;
//        return view('forecast_list', ['precipitation' => $precipitation, 'temperatures' => $temperatures, 'cloudCovers' => $cloudCovers, 'labels' => $labels]);
    }
    
    
    public function read_daily_api()
    {
        $url = "https://api.open-meteo.com/v1/forecast?latitude=52.5244&longitude=13.4105&daily=temperature_2m_max,temperature_2m_min,sunshine_duration,rain_sum&timezone=Europe%2FBerlin&forecast_days=1";
        
        $response = $this->callApi($url);
        
/*        echo  $response['daily']['temperature_2m_min'][0]. "<br>";
        echo  $response['daily']['temperature_2m_max'][0]. "<br>";
        echo  $response['daily']['sunshine_duration'][0]. "<br>";
        echo  $response['daily']['rain_sum'][0]. "<br>";
*/        
        $wf = new WeatherForecast();
        
        $wf->min_temp = $response['daily']['temperature_2m_min'][0];
        $wf->max_temp = $response['daily']['temperature_2m_max'][0];
        $wf->sunshine_duration = $response['daily']['sunshine_duration'][0];
        $wf->rain_sum = $response['daily']['rain_sum'][0];
        $wf->day = $response['daily']['time'][0];

//        $wf->max_temp = 20;
            
        if ($wf->max_temp > 24)
            $wf->classification=3;
        else if ($wf->rain_sum > 5)
            $wf->classification=1;
        else 
            $wf->classification=2;
                    
        return $wf;
    }
    
    public function callApi($url)
    {
        // API aufrufen und JSON-Antwort bekommen
        $response = Http::get($url);
        
        // Prüfen ob der Aufruf erfolgreich war (Status 200)
        if ($response->successful()) {
            // JSON-Antwort als Array
            $data = $response->json();
            
            // Hier kannst du mit $data weiterarbeiten
            return  $data;
        } else {
            // Fehlerbehandlung, wenn API-Aufruf fehlschlägt
            return response()->json([
                'success' => false,
                'message' => 'API call failed',
                'status' => $response->status(),
            ], $response->status());
        }
    }
    
}