<?php

namespace App\Jobs;

use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\WateringDecision;
use App\Models\WiFiSocket;
use App\Models\Zone;
use App\Services\MQTTController;
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
        $this->switch_off_433mhz_sockets();
        $this->switch_off_wifi_sockets();
        $this->switch_off_ha_triggers();
        $this->publish_mqtt_to_ha();
        
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
            $mqttcontroller = new MQTTController();
            $mqttcontroller->send_status_message("relay", $relay->id, "OFF");
            
        }
    }

    private function switch_off_433mhz_sockets()
    {
        $sensor = Sensor::where('sensor_type', '1')->where('enabled', '1')->first();
        $remoteSockets = RemoteSocket::with('zone')->get();
        
        $controller = new WateringController();
        foreach ($remoteSockets as $remoteSocket)
        {
            Log::info('switching  off 433mhz socket ' . $remoteSocket->name);
            $controller->control_433mhz_socket($sensor->gpio_out, $remoteSocket->code_off);
            sleep(1);
            $mqttcontroller = new MQTTController();
            $mqttcontroller->send_status_message("433mhz_socket", $remoteSocket->id, "OFF");
            
        }
    }
    
    private function switch_off_wifi_sockets()
    {
        $wifiSockets = WiFiSocket::with('zone')->get();
        
        $controller = new WateringController();
        foreach ($wifiSockets as $wifiSocket)
        {
            Log::info('switching  off wifi socket ' . $wifiSocket->name);
            $controller->control_wifi_socket($wifiSocket->url_off);
            sleep(1);
            $mqttcontroller = new MQTTController();
            $mqttcontroller->send_status_message("wifi_socket", $wifiSocket->id, "OFF");
            
        }
    }
    
    private function switch_off_ha_triggers()
    {
        $zones = Zone::where('enabled', true)->where('id', '>', '1')->get();
        $mqttcontroller = new MQTTController();
        
        foreach ($zones as $zone)
        {
            $wateringDecision = new WateringDecision();
            $wateringDecision->zone_id=$zone->id;
            $wateringDecision->watering_classification=1;
            $mqttcontroller->send_ha_watering($wateringDecision);       
        }
    }
    
    private function publish_mqtt_to_ha()
    {
        $controller = new MQTTController();
        $controller->send_discovery_messages();
    }
    
}
