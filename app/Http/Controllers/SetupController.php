<?php

namespace App\Http\Controllers;

use App\Models\PercentageConversion;
use App\Models\RemoteSocket;
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
}
