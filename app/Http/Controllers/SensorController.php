<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessData;
use App\Models\Picture;
use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\SensorValue;
use App\Models\WateringDecision;
use App\Models\WeatherForecast;
use App\Models\Zone;
use App\Services\SensorReader;
use App\Helpers\GlobalStuff;
use App\Services\WateringController;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SensorController extends Controller
{
    public function show_manual_watering()
    {
        $horizon = Carbon::now()->subDays(2)->toDateString();
        $manual_waterings = WateringDecision::where('day', '>=', $horizon)->where('type', '2')->get();
        $zones = Zone::all();
        
        return view('manual_watering_list', ['zones' => $zones, 'manual_waterings' => $manual_waterings]);
    }

    public function show_manual_watering2(Request $request)
    {
        $wd = new WateringDecision();
        $wd->zone_id = $request->zone_id;
        $wd->watering_classification = $request->watering_classification;
        $wd->forecast_classification = 0;
        $wd->humidity_classification = 0;
        $wd->type = 2;
        $wd->executed = 1;
        $wd->day =$request->day;
        $wd->tod =$request->tod;
        $wd->save();
        
        $horizon = Carbon::now()->subDays(2)->toDateString();
        $manual_waterings = WateringDecision::where('day', '>=', $horizon)->where('type', '2')->get();
        $zones = Zone::all();
        
        return view('manual_watering_list', ['zones' => $zones, 'manual_waterings' => $manual_waterings]);
    }
    
    
    public function show_zones()
    {
        $zones = Zone::all();
        $sensors = Sensor::all();
        $remoteSockets = RemoteSocket::all();
        
        return view('zone_list', ['zones' => $zones, 'sensors' => $sensors, 'remoteSockets' => $remoteSockets]);
    }
    
    public function show_zone_details($id)
    {
        $zone = Zone::find($id);
        
        $sensors = Sensor::where('zone_id', $id)->where('enabled', 1)->get();
                
        $horizon = Carbon::now()->subDays(2)->toDateString();
        echo ($horizon);

        $timeSeries = [];
        
        $temp_history = SensorValue::where('type', '1')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        
        $labels = [];
        $temperatures = [];
        foreach ($temp_history as $entry) 
        {
            $labels[] = $entry->created_at->format('Y-m-d H:i'); // einheitliche Zeitachse
            $temperatures[] = $entry->value;
        }
        $timeSeries[] = ['name' => 'Temperature Sensor',
            'unit' => '°C',
            'values' => $temperatures,
        ];

        foreach($sensors as $sensor)
        {
            $moistures = SensorValue::where('type', '4')->where('sensor_id', $sensor->id)->where('day', '>=', $horizon)->orderBy('created_at')->get();
            
            if (!$moistures->isEmpty())
            {
                $data = [];
                foreach ($temp_history as $temp)
                {
                    $temp_day=$temp->day;
                    $temp_hour=$temp->hour;
                    $found = false;
                    foreach ($moistures as $mois)
                    {
                        $mois_hour = $mois->hour;
                        $mois_day = $mois->day;
                        if ($temp_day == $mois_day and $temp_hour == $mois_hour)
                        {
                            //                    echo $temp_day . " " . $temp_hour . " " . $forecast_day . " " . $forecast->max_temp . " <br>";
                            $found = true;
                            $data[] = $mois->value;
                            break;
                        }
                    }
                    if (!$found)
                        $data[] = 0.0;
                }
                $timeSeries[] = ['name' => 'Soil Moisture ' . $sensor->name,
                    'unit' => 'V',
                    'values' => $data,
                ];
            }
        }
        
        $forecast_history = WeatherForecast::where('day', '>=', $horizon)->get();
        $forecast_max = [];
        $forecast_min = [];
        $rain_sum = [];
        foreach ($temp_history as $temp)
        {
            $temp_day=$temp->day;
            $temp_hour=$temp->hour;
            $found = false;
            foreach ($forecast_history as $forecast)
            {
                $forecast_day=$forecast->day;
//                echo $temp_day . " " . $forecast_day . " " . $forecast->max_temp . " <br>";
                if ($temp_day == $forecast_day)
                {
//                    echo $temp_day . " " . $temp_hour . " " . $forecast_day . " " . $forecast->max_temp . " <br>";
                    $found = true;
                    $forecast_max[] = $forecast->max_temp;
                    $forecast_min[] = $forecast->min_temp;
                    $rain_sum[] = $forecast->rain_sum;
                    break;
                }
            }
            if (!$found)
            {
                $forecast_max[] = 20.0;
                $forecast_min[] = 10.0;
                $rain_sum[] = 0.0;
            }
        }
        $timeSeries[] = ['name' => 'Forecast Max Temperature',
            'unit' => '°C',
            'values' => $forecast_max,
        ];
        $timeSeries[] = ['name' => 'Forecast Min Temperature',
            'unit' => '°C',
            'values' => $forecast_min,
        ];
        $timeSeries[] = ['name' => 'Forecast Rain Sum',
            'unit' => 'mm',
            'values' => $rain_sum,
        ];
        
        $decisions = WateringDecision::where('zone_id', $id)->where('day', '>=', $horizon)->where('type', '1')->get();
        $manual_decisions = WateringDecision::where('zone_id', $id)->where('day', '>=', $horizon)->where('type', '2')->get();
        
        $watering = [];
        $manual_watering = [];
        foreach ($temp_history as $temp)
        {
            $day=$temp->created_at->format(('Y-m-d'));
            $tod=GlobalStuff::get_tod_from_hour($temp->created_at->format('H'));
            $ifh=GlobalStuff::is_first_hour_of_tod($temp->created_at->format('H'));
//            echo $day . " " . $temp->created_at->format('H') . " " . $tod . " " . $ifh . " ";
            $found = false;
            foreach ($decisions as $dec)
            {
                if ($day == $dec->day and
                    $tod == $dec->tod)
                {
                    $found = true;
                    if ($ifh)
                    {
                        $watering[] = $dec->watering_classification;
                    }
                    else
                       $watering[] = 0;
                    break;
                }
            }
            if (!$found)
            {
                $watering[] = 0;
            }

            $found = false;
            foreach ($manual_decisions as $dec)
            {
                if ($day == $dec->day and
                    $tod == $dec->tod)
                {
                    $found = true;
                    if ($ifh)
                    {
                        $manual_watering[] = 3;
                    }
                    else
                        $manual_watering[] = 0;
                    break;
                }
            }
            if (!$found)
            {
                $manual_watering[] = 0;
            }
        }
        $timeSeries[] = ['name' => 'Watering',
            'unit' => 'l',
            'values' => $watering,
        ];
        $timeSeries[] = ['name' => 'Manual Watering',
            'unit' => 'l',
            'values' => $manual_watering,
        ];
        
//        print_r($timeSeries);
//        echo("<br>");
//        print_r($labels);
//        print_r($temp_history);
//        print_r($forecast_history);
        
        $thresholds = [
            ['y' => 1.7, 'unit' => 'V', 'label' => 'Soil Moisture 1'],
            ['y' => 2.3, 'unit' => 'V', 'label' => 'Soil Moisture 2']
        ];
        
        return view('zone_details', ['zone'=>$zone, 'timeseries' => $timeSeries, 'labels' => $labels, 'decisions' => $decisions, 'manual_decisions' => $manual_decisions, 'thresholds' => $thresholds]);
    }
    
    public function show_sensors()
    {
        $sensors = Sensor::all();
        
        return view('sensor_list', ['sensors' => $sensors]);
    }
    
    public function show_remote_sockets()
    {
        $remoteSockets = RemoteSocket::all();
        
        return view('remote_socket_list', ['remoteSockets' => $remoteSockets]);
    }
    
    public function control_remote_socket(Request $request)
    {
        echo $request->action;
        echo $request->id;

        $sensor = Sensor::where('sensor_type', '1')->first();
        
        $remoteSocket = RemoteSocket::find($request->id);
        
        $code = $remoteSocket->code_on;
        if ($request->action == "off")
            $code = $remoteSocket->code_off;
        
         $controller = new WateringController();
         $controller->control_remote_socket($sensor->gpio_out, $code);
        
        $remoteSockets = RemoteSocket::all();
        
        return view('remote_socket_list', ['remoteSockets' => $remoteSockets]);
    }

    public function show_relays()
    {
        $relays = Sensor::where('sensor_type', '3')->get();
        
        return view('relay_list', ['relays' => $relays]);
    }

    public function control_relays(Request $request)
    {
        echo $request->action;
        echo $request->id;
        
        $sensor = Sensor::find($request->id);
        
        
        if ($request->action == "on")
            $code = 0;
        else
           $code = 1;
                
           $controller = new WateringController();
           $controller->control_relay($sensor->gpio_out, $code);
            
            
        $relays = Sensor::where('sensor_type', '3')->get();
        return view('relay_list', ['relays' => $relays]);
    }

    
    public function show_temperatures()
    {
        $sensors = Sensor::where('sensor_type', '4')->get();
        $reader = new SensorReader();        
        $readings = $reader->read_temperatures($sensors);

        $horizon = Carbon::now()->subDays(2)->toDateString();
        $history = SensorValue::where('type', '1')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        
        // Extrahiere Zeit (X-Achse) und Temperaturwerte (Y-Achse)
        $labels = [];
        $temperatures = [];
        
        foreach ($history as $entry) {
            $labels[] = $entry->created_at->format('Y-m-d H:i'); // oder nur Zeit
            $temperatures[] = $entry->value;
        }
        
        return view('temperature_list', [
            'sensors' => $sensors,
            'readings' => $readings,
            'history' => $history,
            'labels' => $labels,
            'temperatures' => $temperatures
        ]);
    }

    
    public function show_distances()
    {
        $sensors = Sensor::where('sensor_type', '5')->get();
        $reader = new SensorReader();
        $readings = $reader->read_distances($sensors);
        
        return view('distance_list', ['sensors' => $sensors, 'readings'=>$readings]);
    }

    public function show_soil_moistures()
    {
        $sensors = Sensor::where('sensor_type', '6')->get();
        $reader = new SensorReader();
        $readings = $reader->read_humidities($sensors);
        
        $horizon = Carbon::now()->subDays(2)->toDateString();
        $history = SensorValue::where('type', '4')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        $datasets = [];
        $labelSet = [];

        // Gruppiere Werte nach Sensor
        $grouped = $history->groupBy('sensor_id');
        
        foreach ($grouped as $sensorId => $values) 
        {
            $sensor = $values->first()->sensor ?? null;
            $label = $sensor ? $sensor->name : "Sensor $sensorId";
            
            $data = [];
            $labels = [];
            
            foreach ($values as $entry) 
            {
                $labels[] = $entry->created_at->format('Y-m-d H:i'); // einheitliche Zeitachse pro Sensor
                $data[] = $entry->value;
            }
            
            $datasets[] = [
                'label' => $label,
                'data' => $data,
            ];
            
            // Für die Labels nehmen wir einfach die Zeitpunkte des ersten Sensors
            if (empty($labelSet)) {
                $labelSet = $labels;
            }
        }
        
        return view('soil_moisture_list', ['sensors' => $sensors, 'readings'=>$readings, 'history'=>$history, 'datasets' => $datasets, 'labels' => $labelSet]);
    }

    public function show_camera()
    {
        $cameras = Sensor::where('sensor_type', '7')->get();
        $readings = [];
        
        foreach ($cameras as $camera)
        {
            if ($camera->enabled > 0)
            {
            }
        }
        
        $pictures = null;
        $history = Picture::all();
        return view('camera_list', ['cameras' => $cameras, 'pictures'=>$pictures, 'history'=>$history]);
    }
    
    
    public function make_picture(Request $request)
    {
        $cameras = Sensor::where('sensor_type', '7')->get();
        
        $reader = new SensorReader();
        $pictures = $reader->read_camera($cameras);
        
        $history = Picture::all();
        return view('camera_list', ['cameras' => $cameras, 'pictures'=>$pictures, 'history'=>$history]);
    }
    
    
    public function triggerJob()
    {
//        if ($request->adhoc == "true")
//          ProcessData::dispatchSync();
            ProcessData::dispatch();
            //            else
//                SpellcheckBackgroundJob::dispatch($sc);
                
        $sensors = Sensor::all();
        
        return view('sensor_list', ['sensors' => $sensors]);
    }
    
}
