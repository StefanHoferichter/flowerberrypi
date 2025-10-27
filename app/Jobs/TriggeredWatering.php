<?php

namespace App\Jobs;

use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\TriggeredWateringDecision;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class TriggeredWatering implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('######### start handling triggered watering job');
                
        $this->execute_triggered_watering_decisions();
        
        Log::info('######### finished handling triggered watering job');
    }
    
    private function execute_triggered_watering_decisions()
    {
        Log::info('start executing triggered watering decisions');
        
            Log::info('start executing watering');
            $decisions = TriggeredWateringDecision::where('executed', 0)->get();
            foreach($decisions as $decision)
            {
                Log::info('executing decision ' . $decision->zone_id . ' watering ' . $decision->watering_classification);
                
                $sensor = Sensor::where('sensor_type', '1')->first();
                $remoteSocket = RemoteSocket::where('zone_id', $decision->zone_id)->first();
                if ($remoteSocket != null)
                {
                    ProcessData::water_via_remote_socket($decision->watering_classification, $sensor, $remoteSocket);
                }
                $relay = Sensor::where('sensor_type', '3')->where('zone_id', $decision->zone_id)->first();
                if ($relay != null)
                {
                    ProcessData::water_via_relay($decision->watering_classification, $relay);
                }
                
                $decision->executed=1;
                $decision->save();
            }
        
    }
    
}
