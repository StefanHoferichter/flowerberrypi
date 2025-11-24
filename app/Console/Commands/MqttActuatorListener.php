<?php

namespace App\Console\Commands;

use App\Models\Sensor;
use App\Services\WateringController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MqttActuatorListener extends Command
{
    protected $signature = 'mqtt:listen-actuators';
    protected $description = 'Listens for actuator MQTT commands and controls GPIO directly';
    
    public function handle()
    {
        $host = config('mqtt.host');
        $port = config('mqtt.port');
        $username = config('mqtt.username');
        $password = config('mqtt.password');
        $clientId=gethostname();
        
        $connectionSettings = (new ConnectionSettings)
        ->setUsername($username)
        ->setPassword($password);
        
        $mqtt = new MqttClient($host, $port, $clientId);
        
        Log::info("Connecting to MQTT broker {$host}:{$port} ...");
        $mqtt->connect($connectionSettings, true);
        
        Log::info("Connected. Listening for actuator commands…  'plant/watering/{$clientId}/actuator/+/set'");
                
        $mqtt->subscribe("plant/watering/actuator/{$clientId}/+/+/set", function (string $topic, string $message) {
            Log::info( "[MQTT] {$topic} = {$message}");
            
            $this->executeActuator($topic, $message);
        }, 0);
            
        $mqtt->loop(true);
    }
    
    private function executeActuator($topic, $message)
    {
        $parts = explode('/', $topic);
        $actuatorType = $parts[4];
        $actuatorName = $parts[5];
        
        Log::info("received set for " . $actuatorName . " type " . $actuatorType . " with message " . $message);
        
        if ($actuatorType == "Relay")
        {
            $relay = Sensor::where('sensor_type', '3')->whereRaw('LOWER(name) = LOWER(?)', [$actuatorName])->first();
            $controller = new WateringController();
            if ($message == "ON")
            {
                Log::info('switching on relay ' . $relay->name);
                $controller->control_relay($relay->gpio_out, 0);
            }
            else
            {
                Log::info('switching off relay ' . $relay->name);
                $controller->control_relay($relay->gpio_out, 1);
            }
        }
    }
    
}


