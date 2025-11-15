<?php

namespace App\Jobs;

use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Services\WateringController;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class StartupJob implements ShouldQueue
{
    use Queueable;

    protected static $alreadyRun = false;
    
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('!!!!!!!!!!! start handling startup job');
        
        if (self::$alreadyRun) 
        {
            Log::info('StartupJob skipped (already run)');
            return;
        }
        
        self::$alreadyRun = true;
        
        $this->switch_off_relays();
        $this->switch_off_remote_sockets();
        
        Log::info('!!!!!!!!!!! end handling startup job');
    }
    
    private function switch_off_relays()
    {
        $relays = Sensor::where('sensor_type', '3')->where('enabled', '1')->get();
        $controller = new WateringController();
        foreach ($relays as $relay)
        {
            Log::info('switching off relay ' . $relay->name);
            $controller->control_relay($relay->gpio_out, 1);
            sleep(1);
        }
    }

    private function switch_off_remote_sockets()
    {
        $sensor = Sensor::where('sensor_type', '1')->where('enabled', '1')->first();
        $remoteSockets = RemoteSocket::with('zone')->get();
        
        $controller = new WateringController();
        foreach ($remoteSockets as $remoteSocket)
        {
            Log::info('switching  off remote socket ' . $remoteSocket->name);
            $controller->control_remote_socket_old($sensor->gpio_out, $remoteSocket->code_off);
            sleep(1);
        }
    }
}
