<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Collection;

use App\Models\Sensor;
use App\Models\RemoteSocket;
use App\Models\TemperatureSensorResult;
use App\Models\SensorResult;
use App\Services\SensorReader;

class SensorController extends Controller
{
    
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
        
        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_send_433mhz.py ' . $code . ' ' . $sensor->gpio_out);
        echo $output;
        
        
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
                
//           echo('python /var/www/html/flowerberrypi/app/python/php_set_relay.py ' . $sensor->gpio_out . ' ' . $code);
           $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_set_relay.py '. $sensor->gpio_out. ' ' . $code  );
        echo $output;
            
            
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
        
        return view('humidity_list', ['sensors' => $sensors, 'readings'=>$readings]);
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
        
        $filename = null;
        return view('camera_list', ['cameras' => $cameras, 'filename'=>$filename]);
    }
    
    
    public function make_picture(Request $request)
    {
//        echo $request->action;
//        echo $request->id;
        
        $cameras = Sensor::where('sensor_type', '7')->get();
            
        $filename = 'pic_' . date('Y-m-d_H-i-s') . '.jpg';
        echo $filename;
        $output = shell_exec("rpicam-jpeg -o /var/www/html/flowerberrypi/public/" . $filename);
        echo $output;
        
            
        return view('camera_list', ['cameras' => $cameras, 'filename'=>$filename]);
    }
    
}
