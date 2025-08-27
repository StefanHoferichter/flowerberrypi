<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;


class WateringController
{
    public function control_remote_socket($gpio_out, $code)
    {
        Log::info('start controlling remote socket ' . $code . ' ' . $gpio_out );
        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_send_433mhz.py ' . $code . ' ' . $gpio_out);
        sleep(1);
        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_send_433mhz.py ' . $code . ' ' . $gpio_out);
        echo $output;
        Log::info('finished controlling remote socket ' . $code . ' ' . $gpio_out . ' ' . $output );
    }
    
    public function control_relay($gpio_out, $code)
    {
        Log::info('start controlling relay ' . $code . ' ' . $gpio_out );
        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_set_relay.py '. $gpio_out. ' ' . $code  );
        echo $output;
        Log::info('finished controlling relay ' . $code . ' ' . $gpio_out . ' ' . $output );
    }
}