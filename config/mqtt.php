<?php

return [
    'host' => env('MQTT_BROKER_HOST', '127.0.0.1'),
    'port' => env('MQTT_BROKER_PORT', 1883),
    'username' => env('MQTT_USERNAME', null),
    'password' => env('MQTT_PASSWORD', null)
];
