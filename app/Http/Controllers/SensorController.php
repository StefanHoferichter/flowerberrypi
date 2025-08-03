<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use App\Models\Sensor;
use App\Models\RemoteSocket;
use App\Models\TemperaturSensorResult;

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

    
    public function show_temperature()
    {
        $sensors = Sensor::where('sensor_type', '4')->get();
        $readings = []; 
        
        foreach ($sensors as $sensor)
        {
//            echo('python /var/www/html/flowerberrypi/app/python/php_read_temp.py '. $sensor->gpio_in);
            $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_read_temp.py '. $sensor->gpio_in);
//            echo $output;
            
            if (strpos($output, 'Fehler') !== false) {
//                echo "Fehler beim Auslesen des DHT11-Sensors.";
            } else {
                list($temp, $hum) = explode(",", trim($output));
//                echo "Temperatur: {$temp} °C<br>";
//                echo "Luftfeuchtigkeit: {$hum} %<br>";
                $newReading = new TemperaturSensorResult();
                $newReading->temperature=$temp;
                $newReading->humidity=$hum;
                $newReading->name=$sensor->name;
                
                array_push($readings, $newReading);
            }
            
        }
        
        return view('temperature_list', ['sensors' => $sensors, 'readings'=>$readings]);
    }
    
}
