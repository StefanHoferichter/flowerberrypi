<?php

namespace App\Http\Controllers;

use App\Models\PercentageConversion;

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
    
}
