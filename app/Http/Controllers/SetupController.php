<?php

namespace App\Http\Controllers;

use App\Models\PercentageConversion;
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
    
}
