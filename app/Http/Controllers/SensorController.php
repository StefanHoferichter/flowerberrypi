<?php

namespace App\Http\Controllers;

use App\Helpers\GlobalStuff;
use App\Jobs\ProcessData;
use App\Models\HourlyWeatherForecast;
use App\Models\ManualWateringDecision;
use App\Models\Picture;
use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\SensorJob;
use App\Models\SensorValue;
use App\Models\TriggeredWateringDecision;
use App\Models\WateringDecision;
use App\Models\Zone;
use App\Services\ForecastReader;
use App\Services\SensorReader;
use App\Services\WateringController;
use App\Jobs\TriggeredWatering;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    public function show_automated_watering(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $zones = Zone::all();
        $waterings = WateringDecision::where('day', '>=', $horizon)->get();
        $form_url = "/automated_watering";
        
        return view('automated_watering_list', ['zones' => $zones, 'waterings' => $waterings, 'form_url' => $form_url]);
    }
    public function show_manual_watering(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $manual_waterings = ManualWateringDecision::where('day', '>=', $horizon)->get();
        $zones = Zone::all();
        $form_url = "/manual_watering";
        
        return view('manual_watering_list', ['zones' => $zones, 'manual_waterings' => $manual_waterings, 'form_url' => $form_url]);
    }
    
    public function track_watering(Request $request)
    {
        $wd = new ManualWateringDecision();
        $wd->zone_id = $request->zone_id;
        $wd->watering_classification = $request->watering_classification;
        $wd->day =$request->day; 
        $wd->hour =$request->hour;
        $wd->save();
        
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $manual_waterings = ManualWateringDecision::where('day', '>=', $horizon)->get();
        $zones = Zone::all();
        $form_url = "/manual_watering";
        
        return view('manual_watering_list', ['zones' => $zones, 'manual_waterings' => $manual_waterings, 'form_url' => $form_url]);
    }

    public function show_triggered_watering(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $triggered_waterings = TriggeredWateringDecision::where('day', '>=', $horizon)->get();
        $zones = Zone::all();
        $form_url = "/triggered_watering";
        
        return view('triggered_watering_list', ['zones' => $zones, 'triggered_waterings' => $triggered_waterings, 'form_url' => $form_url]);
    }
    public function trigger_watering(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        
        $wd = new TriggeredWateringDecision();
        $wd->zone_id = $request->zone_id;
        $wd->watering_classification = $request->watering_classification;
        $wd->day = date('Y-m-d');
        $wd->hour = date('G');
        $wd->save();
        
        TriggeredWatering::dispatch();

        $triggered_waterings = TriggeredWateringDecision::where('day', '>=', $horizon)->get();
        $zones = Zone::all();
        $form_url = "/triggered_watering";
        
        return view('triggered_watering_list', ['zones' => $zones, 'triggered_waterings' => $triggered_waterings, 'form_url' => $form_url]);
    }
    
    
    public function show_zones()
    {
        $zones = Zone::all();
        $sensors = Sensor::all();
        $remoteSockets = RemoteSocket::all();
        
        return view('zone_list', ['zones' => $zones, 'sensors' => $sensors, 'remoteSockets' => $remoteSockets]);
    }
    
    public function show_zone_details($id, Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $zone = Zone::find($id);
        
        $outdoor = $zone->outdoor;
        $rain_sensitive = $zone->rain_sensitive;
        
        $sensors = Sensor::where('zone_id', $id)->get();
                
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();

        $timeSeries = [];
        
        $temp_history = SensorValue::where('type', '1')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        $hum_history = SensorValue::where('type', '2')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        
        $labels = [];
        $temperatures = [];
        foreach ($temp_history as $entry)
        {
            $labels[] = $entry->created_at->format('Y-m-d H:i'); // einheitliche Zeitachse
            $temperatures[] = $entry->value;
        }
        foreach ($hum_history as $entry)
        {
            $humidities[] = $entry->value;
        }
        if (!$outdoor)
        {
            $timeSeries[] = ['name' => 'Temperature Sensor',
                'color' => GlobalStuff::get_temperature_color(),
                'type' => 'line',
                'unit' => '°C',
                'values' => $temperatures,
            ];
            $timeSeries[] = ['name' => 'Humidity Sensor',
                'color' => GlobalStuff::get_precipitation_color(),
                'type' => 'line',
                'unit' => '%',
                'values' => $humidities,
            ];
        }

        $found_moistures = false;
        $i=0;
        foreach($sensors as $sensor)
        {
            $moistures = SensorValue::where('type', '4')->where('sensor_id', $sensor->id)->where('day', '>=', $horizon)->orderBy('created_at')->get();
            
            if (!$moistures->isEmpty())
            {
                $found_moistures = true;
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
                            $found = true;
                            $data[] = $mois->value;
                            break;
                        }
                    }
                    if (!$found)
                        $data[] = 0.0;
                }
                $timeSeries[] = ['name' => 'Soil Moisture ' . $sensor->name,
                    'color' => GlobalStuff::get_soil_moisture_color($i),
                    'type' => 'line',
                    'unit' => '%',
                    'values' => $data,
                ];
                $i++;
            }
        }

        $found_tank = false;
        foreach($sensors as $sensor)
        {
            $distances = SensorValue::where('type', '3')->where('sensor_id', $sensor->id)->where('day', '>=', $horizon)->orderBy('created_at')->get();
            
            if (!$distances->isEmpty())
            {
                $found_tank = true;
                $data = [];
                foreach ($temp_history as $temp)
                {
                    $temp_day=$temp->day;
                    $temp_hour=$temp->hour;
                    $found = false;
                    foreach ($distances as $dist)
                    {
                        $dist_hour = $dist->hour;
                        $dist_day = $dist->day;
                        if ($temp_day == $dist_day and $temp_hour == $dist_hour)
                        {
                            $found = true;
                            $data[] = $dist->value;
                            break;
                        }
                    }
                    if (!$found)
                        $data[] = 0.0;
                }
                $timeSeries[] = ['name' => 'Water level ' . $sensor->name,
                    'color' => GlobalStuff::get_water_level_color(),
                    'type' => 'line',
                    'unit' => '%',
                    'values' => $data,
                ];
            }
        }

        $hourly_forecast_temperature = [];
        $hourly_forecast_precipitation = [];
        $hourly_forecast_history = HourlyWeatherForecast::where('day', '>=', $horizon)->get();
        foreach ($temp_history as $temp)
        {
            $temp_day=$temp->day;
            $temp_hour=$temp->hour;
            $found = false;
            foreach ($hourly_forecast_history as $forecast)
            {
                $forecast_day=$forecast->day;
                $forecast_hour=$forecast->hour;
                if ($temp_day == $forecast_day and $forecast_hour==$temp_hour)
                {
                    $found = true;
                    $hourly_forecast_temperature[] = $forecast->temperature;
                    $hourly_forecast_precipitation[] = $forecast->precipitation;
                    $hourly_forecast_cloud_cover[] = $forecast->cloud_cover;
                    break;
                }
            }
            if (!$found)
            {
                $hourly_forecast_temperature[] = 20.0;
                $hourly_forecast_precipitation[] = 0.0;
            }
        }
        if ($outdoor)
        {
            $timeSeries[] = ['name' => 'Forecast Temperature',
                'color' => GlobalStuff::get_temperature_color(),
                'type' => 'line',
                'unit' => '°C',
                'values' => $hourly_forecast_temperature,
            ];
            if ($rain_sensitive)
            {
                $timeSeries[] = ['name' => 'Precipitation',
                    'color' => GlobalStuff::get_precipitation_color(),
                    'type' => 'line',
                    'unit' => 'mm',
                    'values' => $hourly_forecast_precipitation,
                ];
            }
        }
        $decisions = WateringDecision::where('zone_id', $id)->where('day', '>=', $horizon)->get();
        $manual_decisions = ManualWateringDecision::where('zone_id', $id)->where('day', '>=', $horizon)->get();
        
        $watering = [];
        $manual_watering = [];
        foreach ($temp_history as $temp)
        {
            $day=$temp->created_at->format(('Y-m-d'));
            $hour=$temp->created_at->format('H');
            $tod=GlobalStuff::get_tod_from_hour($hour);
            $ifh=GlobalStuff::is_first_hour_of_tod($hour);
            $found = false;
            foreach ($decisions as $dec)
            {
                if ($day == $dec->day and
                    $tod == $dec->tod)
                {
                    $found = true;
                    if ($ifh)
                    {
                        $wc = $dec->watering_classification;
                        if ($wc == 1)
                            $wc = 10.0;
                        if ($wc == 2)
                            $wc = 50.0;
                        if ($wc == 3)
                            $wc = 100.0;
                        $watering[] = $wc;
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
                    $hour == $dec->hour)
                {
                    $found = true;
                    $wc = $dec->watering_classification;
                    if ($wc == 1)
                        $wc = 10.0;
                    if ($wc == 2)
                        $wc = 50.0;
                    if ($wc == 3)
                        $wc = 100.0;
                    $manual_watering[] = $wc;
                    break;
                }
            }
            if (!$found)
            {
                $manual_watering[] = 0;
            }
        }
        $timeSeries[] = ['name' => 'Watering',
            'color' => GlobalStuff::get_watering_color(),
            'type' => 'bar',
            'unit' => '%',
            'values' => $watering,
        ];
        $timeSeries[] = ['name' => 'Manual Watering',
            'color' => GlobalStuff::get_manual_watering_color(),
            'type' => 'bar',
            'unit' => '%',
            'values' => $manual_watering,
        ];
        
        $thresholds = [
            ['y' => GlobalStuff::get_temperature_threshold_low(), 'unit' => '°C', 'label' => 'Temperature 1'],
            ['y' => GlobalStuff::get_temperature_threshold_high(), 'unit' => '°C', 'label' => 'Temperature 2'],
        ];
        
        if ($found_moistures)
        {
            $moisture_thresholds = [
                ['y' => GlobalStuff::get_soil_moisture_threshold_low(), 'unit' => '%', 'label' => 'Soil Moisture 1'],
                ['y' => GlobalStuff::get_soil_moisture_threshold_high(), 'unit' => '%', 'label' => 'Soil Moisture 2'],
            ];
            $thresholds = array_merge($moisture_thresholds, $thresholds);
        }
        if ($found_tank)
        {
            $tank_thresholds = [
                ['y' => GlobalStuff::get_tank_threshold_low(), 'unit' => '%', 'label' => 'Tank 1'],
                ['y' => GlobalStuff::get_tank_threshold_high(), 'unit' => '%', 'label' => 'Tank 2'],
            ];
            $thresholds = array_merge($tank_thresholds, $thresholds);
        }
        
        $form_url = "/zone_details/" . $id;
        
        $whatif_decision = $this->make_watering_whatif_decision($zone);
        
        return view('zone_details', ['zone'=>$zone, 'timeseries' => $timeSeries, 'labels' => $labels, 'decisions' => $decisions, 'manual_decisions' => $manual_decisions, 'whatif_decision' => $whatif_decision, 'thresholds' => $thresholds, 'sensors'=>$sensors, 'form_url' => $form_url]);
    }
    
    public function show_sensors()
    {
        $sensors = Sensor::with('type')->get();
        
        return view('sensor_list', ['sensors' => $sensors]);
    }
    
    public function show_remote_sockets()
    {
        $remoteSockets = RemoteSocket::with('zone')->get();
        
        return view('remote_socket_list', ['remoteSockets' => $remoteSockets]);
    }
    
    public function control_remote_socket(Request $request)
    {
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

    
    public function show_temperatures(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $sensors = Sensor::where('sensor_type', '4')->get();
        $reader = new SensorReader();        
        $readings = $reader->read_temperatures($sensors);

        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $temp_history = SensorValue::where('type', '1')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        $hum_history = SensorValue::where('type', '2')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        
        $labels = [];
        $temperatures = [];
        $humidities = [];
        
        foreach ($temp_history as $entry) {
            $labels[] = $entry->created_at->format('Y-m-d H:i'); // oder nur Zeit
            $temperatures[] = $entry->value;
        }
        foreach ($hum_history as $entry) {
            $humidities[] = $entry->value;
        }
        
        $form_url = "/temperatures";
        
        $thresholds = [
            ['y' => GlobalStuff::get_temperature_threshold_low(), 'unit' => '°C', 'label' => 'Temperature 1'],
            ['y' => GlobalStuff::get_temperature_threshold_high(), 'unit' => '°C', 'label' => 'Temperature 2'],
        ];
        
        $timeSeries[] = ['name' => 'Temperature',
            'color' => GlobalStuff::get_temperature_color(),
            'type' => 'line',
            'unit' => '°C',
            'values' => $temperatures,
        ];
        $timeSeries[] = ['name' => 'Humidity',
            'color' => GlobalStuff::get_precipitation_color(),
            'type' => 'line',
            'unit' => '%',
            'values' => $humidities,
        ];
        
        return view('temperature_list', [
            'sensors' => $sensors,
            'readings' => $readings,
            'temp_history' => $temp_history,
            'hum_history' => $hum_history,
            'labels' => $labels,
            'timeseries' => $timeSeries,
            'form_url' => $form_url,
            'thresholds' => $thresholds
        ]);
    }

    
    public function show_distances(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        
        $sensors = Sensor::where('sensor_type', '5')->get();
        $reader = new SensorReader();
        $readings = $reader->read_distances($sensors);

        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $history = SensorValue::with('sensor')->where('type', '3')->where('day', '>=', $horizon)->orderBy('created_at')->get();

        $temp_history = SensorValue::where('type', '1')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        $labels = [];
        foreach ($temp_history as $entry) {
            $labels[] = $entry->created_at->format('Y-m-d H:i'); // oder nur Zeit
        }
        
        $sensorIds = collect($history)->pluck('sensor_id')->unique()->sort()->values();
        $table = [];
        foreach ($history as $entry) {
            $day = $entry['day'];
            $hour = $entry['hour'];
            
            $timestamp = $day . " " . sprintf("%02d", $hour) . ":00";
            $sensorId = $entry['sensor_id'];
            $value = $entry['value'];
            $classification = $entry['classification'];
            
            $table[$timestamp][$sensorId]['value'] = $value;
            $table[$timestamp][$sensorId]['classification'] = $classification;
        }
        ksort($table);
                
        $i=0;
        $timeSeries = [];
        foreach($sensors as $sensor)
        {
            $distances = SensorValue::where('type', '3')->where('sensor_id', $sensor->id)->where('day', '>=', $horizon)->orderBy('created_at')->get();
            
            if (!$distances->isEmpty())
            {
                $data = [];
                foreach ($temp_history as $temp)
                {
                    $temp_day=$temp->day;
                    $temp_hour=$temp->hour;
                    $found = false;
                    foreach ($distances as $dist)
                    {
                        $dist_hour = $dist->hour;
                        $dist_day = $dist->day;
                        if ($temp_day == $dist_day and $temp_hour == $dist_hour)
                        {
                            $found = true;
                            $data[] = $dist->value;
                            break;
                        }
                    }
                    if (!$found)
                        $data[] = 0.0;
                }
                $timeSeries[] = ['name' => 'Tank ' . $sensor->name,
                    'color' => GlobalStuff::get_soil_moisture_color($i),
                    'type' => 'line',
                    'unit' => '%',
                    'values' => $data,
                ];
                $i++;
            }
        }
        
        $thresholds = [
            ['y' => GlobalStuff::get_tank_threshold_low(), 'unit' => '%', 'label' => 'Tank 1'],
            ['y' => GlobalStuff::get_tank_threshold_high(), 'unit' => '%', 'label' => 'Tank 2'],
        ];
        
        $form_url = "/distances";
        
        return view('distance_list', ['sensors' => $sensors, 'readings'=>$readings, 'sensorIds'=>$sensorIds, 'history' => $table, 'timeseries' => $timeSeries, 'labels' => $labels, 'form_url' => $form_url, 'thresholds' => $thresholds]);
    }

    
    public function show_soil_moistures(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $sensors = Sensor::where('sensor_type', '6')->get();
        $reader = new SensorReader();
        $readings = $reader->read_soil_moistures($sensors);
        
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $temp_history = SensorValue::where('type', '1')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        $labels = [];
        foreach ($temp_history as $entry) {
            $labels[] = $entry->created_at->format('Y-m-d H:i'); // oder nur Zeit
        }
        
        $history = SensorValue::where('type', '4')->where('day', '>=', $horizon)->orderBy('created_at')->get();
        
        $sensorIds = collect($history)->pluck('sensor_id')->unique()->sort()->values();
        $table = [];
        foreach ($history as $entry) {
            $day = $entry['day'];
            $hour = $entry['hour'];
            
            $timestamp = $day . " " . sprintf("%02d", $hour) . ":00";
            $sensorId = $entry['sensor_id'];
            $value = $entry['value'];
            $classification = $entry['classification'];
            
            $table[$timestamp][$sensorId]['value'] = $value;
            $table[$timestamp][$sensorId]['classification'] = $classification;
        }
        ksort($table);

        
        $i=0;
        $timeSeries = [];
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
                            $found = true;
                            $data[] = $mois->value;
                            break;
                        }
                    }
                    if (!$found)
                        $data[] = 0.0;
                }
                $timeSeries[] = ['name' => 'Soil Moisture ' . $sensor->name,
                    'color' => GlobalStuff::get_soil_moisture_color($i),
                    'type' => 'line',
                    'unit' => '%',
                    'values' => $data,
                ];
                $i++;
            }
        }
        
        $form_url = "/soil_moistures";
        
        $thresholds = [
            ['y' => GlobalStuff::get_soil_moisture_threshold_low(), 'unit' => '%', 'label' => 'Soil Moisture 1'],
            ['y' => GlobalStuff::get_soil_moisture_threshold_high(), 'unit' => '%', 'label' => 'Soil Moisture 2'],
        ];
        
        
        return view('soil_moisture_list', ['sensors' => $sensors, 'readings'=>$readings, 'sensorIds'=>$sensorIds, 'history'=>$table, 'timeseries' => $timeSeries, 'labels' => $labels, 'form_url' => $form_url, 'thresholds' => $thresholds]);
    }

    public function show_camera(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);

        $pictures = null;
        
        $cameras = Sensor::where('sensor_type', '7')->get();
        $readings = [];
        
        foreach ($cameras as $camera)
        {
            if ($camera->enabled > 0)
            {
            }
        }
        
        
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $history = Picture::where('day', '>=', $horizon)->orderBy('created_at')->get();
//        $history = Picture::all();

        $form_url = "/camera";
        
        return view('camera_list', ['cameras' => $cameras, 'pictures'=>$pictures, 'history'=>$history, 'form_url' => $form_url]);
    }
    
    
    public function make_picture(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        
        $cameras = Sensor::where('sensor_type', '7')->get();
        
        $reader = new SensorReader();
        $pictures = $reader->read_camera($cameras);
        
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $history = Picture::where('day', '>=', $horizon)->orderBy('created_at')->get();
        
        $form_url = "/camera";
        
        return view('camera_list', ['cameras' => $cameras, 'pictures'=>$pictures, 'history'=>$history, 'form_url' => $form_url]);
    }
    
    
    public function triggerJob(Request $request)
    {
        if ($request->adhoc == "true")
            ProcessData::dispatchSync();
        else
            ProcessData::dispatch();
        
        return redirect('/jobs');
    }
    
    public function show_jobs(Request $request)
    {
        $time_horizon_days = $request->query('time_horizon_days', 3);
        $horizon = Carbon::now()->subDays($time_horizon_days)->toDateString();
        $history = SensorJob::where('created_at', '>=', $horizon)->orderBy('created_at')->get();
        $form_url = "/jobs";
        
        return view('sensor_job_list', ['history' => $history, 'form_url' => $form_url]);
    }

    public function show_job_details($id)
    {
        $sensor_values = SensorValue::with('sensor_value_type')->with('sensor')->where('job_id', $id)->orderBy('created_at')->get();
        $pictures = Picture::where('job_id', $id)->orderBy('created_at')->get();
        $watering_decisions = WateringDecision::with('zone')->where('job_id', $id)->get();
        
        return view('job_details_list', ['sensor_values' => $sensor_values, 'pictures' => $pictures, 'watering_decisions' => $watering_decisions]);
    }
    
    
    private function make_watering_whatif_decision($zone)
    {
        Log::info('analyzing zone ' . $zone->name);
        
        $reader = new ForecastReader();
        $wf = $reader->read_daily_api();
        $max_forecast_classification = $wf->classification;
        
        $tempSensors = Sensor::where('sensor_type', 4)->get();
        $distanceSensors = Sensor::where('zone_id', $zone->id)->where('sensor_type', 5)->get();
        $moistureSensors = Sensor::where('zone_id', $zone->id)->where('sensor_type', 6)->get();
        $max_moisture_classification = 0;
        $max_tank_classification = 0;
        $max_temp_classification = 0;
        $reader = new SensorReader();
        $readings = $reader->read_distances($distanceSensors);
        
        foreach($readings as $v)
        {
            Log::info('tank classification ' . $v->classification);
            
            if ($max_tank_classification < $v->classification)
                $max_tank_classification = $v->classification;
        }

        $readings = $reader->read_soil_moistures($moistureSensors);
        foreach($readings as $v)
        {
            Log::info('moisture classification ' . $v->classification);
            
            if ($max_moisture_classification < $v->classification)
                $max_moisture_classification = $v->classification;
        }

        $readings = $reader->read_temperatures($tempSensors);
        foreach($readings as $v)
        {
            Log::info('temp classification ' . $v->classification);
            
            if ($max_temp_classification < $v->classification)
                $max_temp_classification = $v->classification;
        }
        
        $controller = new WateringController();
        $wd = $controller->make_watering_decision($zone, $max_moisture_classification, $max_tank_classification, $max_temp_classification, $max_forecast_classification);

        return $wd;
    }

    
            
}
