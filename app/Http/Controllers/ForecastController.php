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
        
        $history = WeatherForecast::all();
        
        return view('forecast_list', ['forecast'=>$wf, 'history'=>$history]);
    }
    
}
