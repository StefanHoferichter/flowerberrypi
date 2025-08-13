<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessData;
use App\Models\Cycle;
use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\SensorValue;
use App\Services\SensorReader;
use App\Services\WateringController;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    
    public function show_cycles()
    {
        $cycles = Cycle::all();
        $sensors = Sensor::all();
        
        return view('cycle_list', ['cycles' => $cycles, 'sensors' => $sensors]);
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
        
        return view('temperature_list', ['sensors' => $sensors, 'readings'=>$readings]);
    }

    
    public function show_distances()
    {
        $sensors = Sensor::where('sensor_type', '5')->get();
        $reader = new SensorReader();
        $readings = $reader->read_distances($sensors);
        
        return view('distance_list', ['sensors' => $sensors, 'readings'=>$readings]);
    }

    public function show_humidities()
    {
        $sensors = Sensor::where('sensor_type', '6')->get();
        $reader = new SensorReader();
        $readings = $reader->read_humidities($sensors);
        
        $history = SensorValue::where('type', '4')->get();
        $labels = [];
        $values = [];
        
        foreach($history as $h)
        {
            array_push($labels, $h->created_at);
            array_push($values, $h->value);
        }
        return view('humidity_list', ['sensors' => $sensors, 'readings'=>$readings, 'history'=>$history, 'labels'=>$labels, 'values'=>$values]);
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
        return view('camera_list', ['cameras' => $cameras, 'pictures'=>$pictures]);
    }
    
    
    public function make_picture(Request $request)
    {
        $cameras = Sensor::where('sensor_type', '7')->get();
        
        $reader = new SensorReader();
        $pictures = $reader->read_camera($cameras);
        
        return view('camera_list', ['cameras' => $cameras, 'pictures'=>$pictures]);
    }
    
    
    public function triggerJob()
    {
//        if ($request->adhoc == "true")
ProcessData::dispatchSync();
//            ProcessData::dispatch();
            //            else
//                SpellcheckBackgroundJob::dispatch($sc);
                
        $sensors = Sensor::all();
        
        return view('sensor_list', ['sensors' => $sensors]);
    }
    
}
