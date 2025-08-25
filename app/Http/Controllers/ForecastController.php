<?php

namespace App\Http\Controllers;

use App\Models\WeatherForecast;
use App\Services\ForecastReader;

class ForecastController extends Controller
{
    public function read_daily_api()
    {   
        $reader = new ForecastReader();
        $wf = $reader->read_daily_api();
        
        $hwf = $reader->read_hourly_api();
        
        $history = WeatherForecast::all();
        
        foreach ($hwf as $entry) {
            $labels[] = $entry->hour; // oder nur Zeit
            $temperatures[] = $entry->temperature;
            $precipitation[] = $entry->precipitation;
            $cloud_cover[] = $entry->cloud_cover;
        }
        
        
        return view('forecast_list', ['forecast'=>$wf, 'hourly_forecast'=>$hwf, 'history'=>$history, 'labels'=>$labels, 'temperatures'=>$temperatures, 'precipitation'=>$precipitation, 'cloud_cover'=>$cloud_cover]);
    }
    
}
