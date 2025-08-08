<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Collection;

use App\Models\Sensor;
use App\Jobs\ProcessSensorReadings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ForecastController extends Controller
{
    
    public function read_hourly_api()
    {
        $url = "https://api.open-meteo.com/v1/forecast?latitude=52.5244&longitude=13.4105&hourly=temperature_2m,precipitation,cloud_cover&forecast_days=1";
        
        $response = $this->callApi($url);
        
//        print_r( $response);
//        echo "<br>";
        $precipitation = $response['hourly']['precipitation'];
        foreach($precipitation as $prec)
        {
//            echo $prec . "<br>";
        }
        $temperatures = $response['hourly']['temperature_2m'];
        foreach($temperatures as $temp)
        {
//            echo $temp . "<br>";
        }
        $cloudCovers = $response['hourly']['cloud_cover'];
        foreach($cloudCovers as $cc)
        {
//            echo $cc . "<br>";
        }
        
        $labels = ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00','18:00', '19:00', '20:00', '21:00', '22:00', '23:00'];
        return view('forecast_list', ['precipitation' => $precipitation, 'temperatures' => $temperatures, 'cloudCovers' => $cloudCovers, 'labels' => $labels]);
    }
    
    
    public function read_daily_api()
    {
        $url = "https://api.open-meteo.com/v1/forecast?latitude=52.5244&longitude=13.4105&daily=temperature_2m_max,temperature_2m_min,sunshine_duration,rain_sum&timezone=Europe%2FBerlin&forecast_days=1";
        
        $response = $this->callApi($url);

        echo  $response['daily']['temperature_2m_min'][0]. "<br>";
        echo  $response['daily']['temperature_2m_max'][0]. "<br>";
        echo  $response['daily']['sunshine_duration'][0]. "<br>";
        echo  $response['daily']['rain_sum'][0]. "<br>";
        
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
