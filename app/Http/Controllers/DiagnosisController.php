<?php

namespace App\Http\Controllers;

use App\Services\SensorReader;
use App\Services\WateringController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function show_diagnosis()
    {
        return view('diagnosis');
    }
    
    public function show_i2c_bus()
    {
        $reader = new SensorReader();
        $output = $reader->read_i2c_bus();
        
        return view('i2c_bus', ['output' => $output]);
    }
    
    public function show_433mhz_start()
    {
        $output[] = '';
        $timeout = 10;
        
        return view('433mhz_list', ['output' => $output, 'timeout' => $timeout]);
    }
    public function show_433mhz(Request $request)
    {
        $timeout = $request->get('timeout'); 
        
        // 2. Validierung (wichtig!)
        $validated = $request->validate(['timeout' => 'required|integer|min:1',]);
        
        $reader = new WateringController();
        $output = $reader->sniff($timeout);
        $lines = array_values(array_filter(array_map('trim', explode("\n", $output))));
        foreach($lines as $line)
        {
            Log::info('#'. $line . '*');
        }
        
        return view('433mhz_list', ['output' => $lines, 'timeout' => $timeout]);
    }

    public function show_pcb_version()
    {
        $reader = new SensorReader();
        $output = $reader->read_pcb_version();
        
        return view('pcb_version', ['output' => $output]);
    }
    
}
