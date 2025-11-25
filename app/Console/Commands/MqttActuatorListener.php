<?php

namespace App\Console\Commands;

use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Services\MQTTController;
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
        Log::info( "############### listener started");
        
        $host = config('mqtt.host');
        $port = config('mqtt.port');
        $username = config('mqtt.username');
        $password = config('mqtt.password');
        $clientId=gethostname();
        
        $connectionSettings = (new ConnectionSettings)
        ->setUsername($username)
        ->setPassword($password)
        ->setKeepAliveInterval(30)   
        ->setReconnectAutomatically(true);
        
        if (empty($host))
        {
            Log::info('MQTT disabled');
            return;
        }
            
        $mqtt = new MqttClient($host, $port, $clientId . "-listener");
        try
        {
            Log::info("Connecting to MQTT broker {$host}:{$port} ...");
            $mqtt->connect($connectionSettings, false);
            $topic = "plant/watering/actuator/{$clientId}/+/+/set";
            Log::info("Connected. Listening for actuator commands… " .  $topic);
            
            $mqtt->subscribe($topic, function (string $topic, string $message) use ($mqtt)
            {
                Log::info( "[§§§§§§§§§§§§§§§§§§§§§] {$topic} = {$message}");
    
                $parts = explode('/', $topic);
                $actuatorType = $parts[4];
                $actuatorId = $parts[5];
                
                $this->executeActuator($actuatorType, $actuatorId, $message);
    
                $controller = new MQTTController();
                $controller->send_status_message($actuatorType, $actuatorId, $message);
                      
                
                Log::info( "[§§§§§§§§§§§§§§§§§§§§§] finished");
            }, 0);
                
            $mqtt->loop(true);
        }
        catch (\PhpMqtt\Client\Exceptions\MqttClientException $e)
        {
            Log::error("MQTT error: " . $e->getMessage());
        }
        
        Log::info( "############### listener finished");
        
    }
    
    private function executeActuator($actuatorType, $actuatorId, $message)
    {
        
        Log::info("received set for " . $actuatorId . " type " . $actuatorType . " with message " . $message);
        
        if ($actuatorType == "relay")
        {
            $relay = Sensor::where('sensor_type', '3')->where('id', $actuatorId)->first();
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
        if ($actuatorType == "remote_socket")
        {
            Log::info('switching remote_socket ');
            $sensor = Sensor::where('sensor_type', '1')->first();
            Log::info('switching remote_socket ' . $sensor->gpio_out);
            $remoteSocket = RemoteSocket::where('id', $actuatorId)->first();
            Log::info('switching remote_socket ' . $remoteSocket->name . " - " . $sensor->gpio_out);
            $controller = new WateringController();
            if ($message == "ON")
            {
                Log::info('switching on remote_socket ' . $remoteSocket->name);
                $controller->control_remote_socket_old($sensor->gpio_out, $remoteSocket->code_on);
            }
            else
            {
                Log::info('switching off remote_socket ' . $remoteSocket->name);
                $controller->control_remote_socket_old($sensor->gpio_out, $remoteSocket->code_off);
            }
        }

    }
}


