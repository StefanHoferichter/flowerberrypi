<?php

namespace App\Http\Controllers;

use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class MQTTController extends Controller
{
    public function send_discovery_message()
    {
        // MQTT Broker-Daten
        $broker   = '192.168.1.44';
        $port     = 1883;
        $clientId = 'flowerberrypi4b';
        $username = 'mqtt';
        $password = 'qqbus09MQTT';
        
        // Verbindungseinstellungen
        $connectionSettings = (new ConnectionSettings)
        ->setUsername($username)
        ->setPassword($password)
        ->setKeepAliveInterval(60);
        
        $mqtt = new MqttClient($broker, $port, $clientId);
        
        try {
            $mqtt->connect($connectionSettings, true);
            
            // --- MQTT Discovery Topic ---
            $topic = "homeassistant/sensor/{$clientId}_temperature/config";
            
            // --- Payload für Discovery ---
            $payload = [
                "device_class" => "temperature",
                "name" => "TestPi Temperatur",
                "unique_id" => "{$clientId}_temperature",
                "state_topic" => "homeassistant/device/{$clientId}/temperature",
                "device_class" => "temperature",
                "unit_of_measurement" => "°C",
                "value_template" => "22",
                "platform" => "mqtt",
                "device" => [
                    "identifiers" => [$clientId],
                    "name" => "{$clientId}",
                    "manufacturer" => "SHSS",
                    "model" => "RPi Controller"
                ]
                ];
            
            // Discovery-Nachricht senden
            $mqtt->publish($topic, json_encode($payload), 0, true);
            
            // --- Initialwert senden ---
            $stateTopic = "homeassistant/device/{$clientId}/temperature";
            $statePayload = "22.0";
            $mqtt->publish($stateTopic, $statePayload, 0, true);
            
            echo "Discovery + initialer Wert erfolgreich gesendet!\n";
            
            $mqtt->disconnect();
        } catch (\PhpMqtt\Client\Exceptions\MqttClientException $e) {
            echo "Fehler beim Verbinden oder Senden: " . $e->getMessage() . "\n";
        }
    }
}
