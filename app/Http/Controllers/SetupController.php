<?php

namespace App\Http\Controllers;

use App\Models\PercentageConversion;
use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\SensorType;
use App\Models\Zone;
use Illuminate\Http\Request;

class SetupController extends Controller
{
    public function show_setup()
    {
        return view('setup');
    }
    public function show_percentage_conversions()
    {
        $pcs = PercentageConversion::all();
        return view('percentage_conversions', ['pcs' => $pcs]);
    }
    public function save_percentage_conversions(Request $request)
    {
        $id = $request->id;
        $pc = PercentageConversion::find($id);
        $pc->lower_limit = $request->lower_limit;
        $pc->upper_limit = $request->upper_limit;
        if ($request->invert > 0)
            $pc->invert = 1;
        else 
            $pc->invert = 0;
            
        $pc->save();
        
        $pcs = PercentageConversion::all();
        return view('percentage_conversions', ['pcs' => $pcs]);
    }
    
    public function show_remote_sockets()
    {
        $rss = RemoteSocket::all();
        $zones = Zone::all();
        return view('remote_sockets', ['remoteSockets' => $rss, 'zones' => $zones]);
    }
    public function save_remote_sockets(Request $request)
    {
        $id = $request->id;
        $rs = RemoteSocket::find($id);
        $rs->name= $request->name;
        $rs->code_on= $request->code_on;
        $rs->code_off = $request->code_off;
        $rs->zone_id = $request->zone_id;
        
        $rs->save();
        
        $rss = RemoteSocket::all();
        $zones = Zone::all();
        return view('remote_sockets', ['remoteSockets' => $rss, 'zones' => $zones]);
    }

    public function show_sensors()
    {
        $sensors = Sensor::all();
        $sensor_types = SensorType::all();
        $zones = Zone::all();
        return view('sensors', ['sensors' => $sensors, 'zones' => $zones, 'sensor_types' => $sensor_types]);
    }
    public function save_sensors(Request $request)
    {
        $id = $request->id;
        $sensor = Sensor::find($id);
        $sensor->name= $request->name;
        $sensor->sensor_type= $request->sensor_type;
        $sensor->zone_id = $request->zone_id;
        $sensor->gpio_in = $request->gpio_in;
        $sensor->gpio_out = $request->gpio_out;
        $sensor->gpio_extra = $request->gpio_extra;
        
        echo $id . "-" . $request->name;
        
        $sensor->save();
        
        $sensors = Sensor::all();
        $sensor_types = SensorType::all();
        $zones = Zone::all();
        return view('sensors', ['sensors' => $sensors, 'zones' => $zones, 'sensor_types' => $sensor_types]);
    }
    
}
