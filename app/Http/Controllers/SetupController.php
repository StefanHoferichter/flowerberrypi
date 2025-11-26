<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\PercentageConversion;
use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\SensorType;
use App\Models\SensorValueType;
use App\Models\Threshold;
use App\Models\User;
use App\Models\WiFiSocket;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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
        $rs = RemoteSocket::all();
        $ws = WiFiSocket::all();
        $zones = Zone::all();
        return view('remote_sockets', ['remoteSockets' => $rs, 'wifiSockets' => $ws, 'zones' => $zones]);
    }
    public function save_433mhz_sockets(Request $request)
    {
        $id = $request->id;
        $rs = RemoteSocket::find($id);
        $rs->name= $request->name;
        $rs->code_on= $request->code_on;
        $rs->code_off = $request->code_off;
        $rs->zone_id = $request->zone_id;
        
        $rs->save();
        
        $rs = RemoteSocket::all();
        $ws = WiFiSocket::all();
        $zones = Zone::all();
        return view('remote_sockets', ['remoteSockets' => $rs, 'wifiSockets' => $ws, 'zones' => $zones]);
    }
    public function save_wifi_sockets(Request $request)
    {
        $id = $request->id;
        $rs = WiFiSocket::find($id);
        $rs->name= $request->name;
        $rs->url_on= $request->url_on;
        $rs->url_off = $request->url_off;
        $rs->zone_id = $request->zone_id;
        
        $rs->save();
        
        $rs = RemoteSocket::all();
        $ws = WiFiSocket::all();
        $zones = Zone::all();
        return view('remote_sockets', ['remoteSockets' => $rs, 'wifiSockets' => $ws, 'zones' => $zones]);
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
        if ($request->enabled > 0)
            $sensor->enabled = 1;
        else
            $sensor->enabled = 0;
                
        $sensor->save();
        
        $sensors = Sensor::all();
        $sensor_types = SensorType::all();
        $zones = Zone::all();
        return view('sensors', ['sensors' => $sensors, 'zones' => $zones, 'sensor_types' => $sensor_types]);
    }

    public function show_thresholds()
    {
        $thresholds = Threshold::all();
        $sensor_value_types = SensorValueType::all();
        return view('thresholds', ['thresholds' => $thresholds, 'sensor_value_types' => $sensor_value_types]);
    }
    public function save_thresholds(Request $request)
    {
        $id = $request->id;
        $th = Threshold::find($id);
        $th->lower_limit = $request->lower_limit;
        $th->upper_limit = $request->upper_limit;
        $th->save();
                
        $thresholds = Threshold::all();
        $sensor_value_types = SensorValueType::all();
        return view('thresholds', ['thresholds' => $thresholds, 'sensor_value_types' => $sensor_value_types]);
    }

    public function show_zones()
    {
        $zones = Zone::all();
        return view('zones', ['zones' => $zones]);
    }
    public function save_zones(Request $request)
    {
        $id = $request->id;
        $zone = Zone::find($id);
        $zone->name = $request->name;
        if ($request->enabled > 0)
            $zone->enabled = 1;
        else
            $zone->enabled = 0;
        
        if ($request->has_watering > 0)
            $zone->has_watering = 1;
        else
            $zone->has_watering = 0;
                    
        if ($request->rain_sensitive > 0)
            $zone->rain_sensitive = 1;
        else
            $zone->rain_sensitive= 0;

        if ($request->outdoor > 0)
            $zone->outdoor = 1;
        else
            $zone->outdoor = 0;
                    
        $zone->save();
                
        $zones = Zone::all();
        return view('zones', ['zones' => $zones]);
    }

    public function show_misc()
    {
        $users = User::all();
        $locations = Location::all();
        return view('setup_misc', ['locations' => $locations, 'users' => $users]);
    }
    
    public function save_password(Request $request)
    {
        
        $request->validate([
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(),
            ],
        ]);
        
        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();
        
        return back()->with('status', 'Password successfully saved.');
    }

    public function save_location(Request $request)
    {
        $validated = $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'timezone'  => ['required', 'string'],
        ]);
        
        $location = Location::first();
        $location->latitude  = $validated['latitude'];
        $location->longitude = $validated['longitude'];
        $location->timezone  = $validated['timezone'];
        $location->save();
        
        return back()->with('status', 'Location successfully saved.');
    }
}
