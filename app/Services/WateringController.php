<?php

namespace App\Services;

use App\Models\SensorResult;
use App\Models\Picture;
use App\Models\TemperatureSensorResult;
use Illuminate\Support\Collection;


class WateringController
{
    public function control_remote_socket($gpio_out, $code)
    {
        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_send_433mhz.py ' . $code . ' ' . $gpio_out);
        echo $output;
    }
    
    public function control_relay($gpio_out, $code)
    {
        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_set_relay.py '. $gpio_out. ' ' . $code  );
        echo $output;
    }
}