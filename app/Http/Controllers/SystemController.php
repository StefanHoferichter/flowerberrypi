<?php

namespace App\Http\Controllers;


use function collect;

class SystemController extends Controller
{
    public function show_home()
    {
        
        return view('home');
    }

    public function shutdown()
    {
        exec('sudo shutdown');
        
        return view('shutdown');
    }

    public function reboot()
    {
        exec('sudo reboot');
        return view('reboot');
    }
}
