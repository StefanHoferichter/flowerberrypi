<?php

namespace App\Http\Controllers;

use App\Models\WeatherForecast;
use App\Services\ForecastReader;
use App\Helpers\GlobalStuff;

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
        
        $timeSeries[] = ['name' => 'Forecast Temperature',
            'color' => GlobalStuff::get_temperature_color(),
            'type' => 'line',
            'unit' => '°C',
            'values' => $temperatures,
        ];
        $timeSeries[] = ['name' => 'Precipitation',
            'color' => GlobalStuff::get_precipitation_color(),
            'type' => 'line',
            'unit' => 'mm',
            'values' => $precipitation,
        ];
        $timeSeries[] = ['name' => 'Cloud Cover',
            'color' => GlobalStuff::get_cloud_cover_color(),
            'type' => 'line',
            'unit' => '%',
            'values' => $cloud_cover,
        ];
        
        $thresholds = [
            ['y' => GlobalStuff::get_temperature_threshold_low(), 'unit' => '°C', 'label' => 'Temperature 1'],
            ['y' => GlobalStuff::get_temperature_threshold_high(), 'unit' => '°C', 'label' => 'Temperature 2'],
        ];
        
        $form_url = "/forecast";
        
        return view('forecast_list', ['forecast'=>$wf, 'hourly_forecast'=>$hwf, 'history'=>$history, 'labels'=>$labels, 'timeseries'=>$timeSeries, 'thresholds'=>$thresholds, 'form_url' => $form_url]);
    }
    
}
