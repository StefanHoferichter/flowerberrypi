<?php

namespace App\Services;

use App\Models\RemoteSocket;
use App\Models\Sensor;
use App\Models\SensorValue;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MQTTController
{
    protected array $sensorTypeMap = [
        1 => ['device_class' => 'temperature', 'unit' => '°C'],
        2 => ['device_class' => 'humidity', 'unit' => '%'],
        3 => ['device_class' => null, 'unit' => '%'],
        4 => ['device_class' => 'moisture', 'unit' => '%'],        
    ];
    
    public function send_discovery_messages()
    {
        $host = config('mqtt.host');
        $port = config('mqtt.port');
        $username = config('mqtt.username');
        $password = config('mqtt.password');
        $clientId=gethostname();
        
        $connectionSettings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setKeepAliveInterval(60);
        
        $mqtt = new MqttClient($host, $port, $clientId);
        
        try {
            $mqtt->connect($connectionSettings, true);
            $clientId=gethostname();
            
            $this->publish_sensors($clientId, $mqtt);
            $this->publish_sensor_values($clientId, $mqtt);
            $this->publish_actuators($clientId, $mqtt);
            
            
            Log::info("discovery message sent");
            
            $mqtt->disconnect();
        } 
        catch (\PhpMqtt\Client\Exceptions\MqttClientException $e) 
        {
            echo "Fehler beim Verbinden oder Senden: " . $e->getMessage() . "\n";
        }
    }

    
    private function publish_sensors($clientId, $mqtt)
    {
        $latestValues = SensorValue::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
            ->from('sensor_values')
            ->groupBy('sensor_id', 'type');
        })->with('sensor')->with('sensor_value_type')
        ->get();
        
        foreach ($latestValues as $value)
        {
            $sensorNameOrig=$value->sensor->name . " " . $value->sensor_value_type->name;
            $sensorName = $this->sanitizeSensorName($sensorNameOrig);
            
            $discoveryTopic = "homeassistant/sensor/{$clientId}/{$sensorName}/config";
            
            $typeConfig = $this->sensorTypeMap[$value->type] ?? [
                'device_class' => null,
                'unit' => null
            ];
            // Payload für Home Assistant Discovery
            $payload = [
                'name' => $sensorNameOrig,
                'state_topic' => "plant/watering/sensor/{$sensorName}/state",
                'unique_id' => "{$clientId}_{$sensorName}",
                "device" => [
                    "identifiers" => [$clientId],
                    "name" => "{$clientId}",
                    "manufacturer" => "SHSS",
                    "model" => "FlowerBerryPi V1.0"
                        ]
                        ];
            if (!empty($typeConfig['unit'])) {
                $payload['unit_of_measurement'] = $typeConfig['unit'];
            }
            if (!empty($typeConfig['device_class'])) {
                $payload['device_class'] = $typeConfig['device_class'];
            }
            
            $mqtt->publish($discoveryTopic, json_encode($payload), 0, true);
            
            Log::info("sensor {$sensorName} message sent");
        }
    }

    private function publish_sensor_values($clientId, $mqtt)
    {
        $latestValues = SensorValue::whereIn('id', function ($query) {
            $query->selectRaw('MAX(id)')
            ->from('sensor_values')
            ->groupBy('sensor_id', 'type');
        })->with('sensor')->get();
        
        foreach ($latestValues as $value) 
        {
            $sensorNameOrig=$value->sensor->name . " " . $value->sensor_value_type->name;
            $sensorName = $this->sanitizeSensorName($sensorNameOrig);
            $stateTopic = "plant/watering/sensor/{$sensorName}/state";
            
            $payload = $value->value; 
            
            $mqtt->publish($stateTopic, $payload, 0, true); 
            
            Log::info("sensor value {$sensorName} message sent");
        }
    }
    private function publish_actuators($clientId, $mqtt)
    {
        $relays = Sensor::where('sensor_type', "3" )->get()->map(fn($r) => [
            'id' => $r->id,
            'name' => $r->name,
            'type' => 'relay'
        ]);
        
        $sockets = RemoteSocket::all()->map(fn($s) => [
            'id' => $s->id,
            'name' => $s->name,
            'type' => 'remote_socket'
        ]);
        
        $actuators = $relays->merge($sockets);
        
        foreach ($actuators as $actuator)
        {
            $actuatorNameOrig=$actuator['name'];
            $actuatorName = $this->sanitizeSensorName($actuatorNameOrig);
            
            $discoveryTopic = "homeassistant/switch/{$clientId}/{$actuatorName}/config";
                        
            $payload = [
                'name' => $actuatorNameOrig,
                'state_topic' => "plant/watering/actuator/{$clientId}/{$actuator['type']}/{$actuator['id']}/state",
                'command_topic' => "plant/watering/actuator/{$clientId}/{$actuator['type']}/{$actuator['id']}/set",
                'payload_on' => "ON",
                'payload_off' => "OFF",
                'unique_id' => "{$clientId}_{$actuatorName}",
                "device" => [
                    "identifiers" => [$clientId],
                    "name" => "{$clientId}",
                    "manufacturer" => "SHSS",
                    "model" => "FlowerBerryPi V1.0"
                        ]
                        ];

            $mqtt->publish($discoveryTopic, json_encode($payload), 0, true);
            
            Log::info("actuator {$actuatorName} message sent");
            
        }
    }
    
    
    private function sanitizeSensorName(string $name): string
    {
        $name = mb_strtolower($name);
        $name = preg_replace('/[^a-z0-9_]/', '_', $name);
        $name = preg_replace('/_+/', '_', $name);
        $name = trim($name, '_');
        
        return $name;
    }
}

