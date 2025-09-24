<?php

namespace App\Services;

use Carbon\Exceptions\Exception;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;


class WateringController
{
    public function control_remote_socket($gpio_out, $code)
    {
        Log::info('start controlling remote socket ' . $code . ' ' . $gpio_out );
        $output = shell_exec('sudo /var/www/html/flowerberrypi/app/python/codesend ' . $code . ' 2>&1');
//        sleep(1);
//        $output = shell_exec('python /var/www/html/flowerberrypi/app/python/php_send_433mhz.py ' . $code . ' ' . $gpio_out);
        echo $output;
        Log::info('finished controlling remote socket ' . $code . ' ' . $gpio_out . ' ' . $output );
    }

    public function control_remote_socket_old($gpio_out, $code)
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
    
    public function sniff($timeout)
    {
        Log::info('start reading 433MHz');
        $cmd = ['sudo', '/var/www/html/flowerberrypi/app/python/RFSniffer'];
        
        $process = new Process($cmd);
        $process->setTimeout($timeout); // optional Timeout
        
        $output = '';
        
        try {
            $process->run();
            
            $output = trim($process->getOutput());
            $errorOutput = trim($process->getErrorOutput());
            
            if (!$process->isSuccessful()) 
            {
                // Beispiel: Device busy oder andere Fehler
                Log::error("RFSniffer failed: $errorOutput");
                return $errorOutput;
            }
            
            // Gib hier den Output zurück, je nachdem wie RFSniffer ihn liefert
            return $output;
            
        }
        catch (ProcessTimedOutException $e)
        {
            $output = trim($process->getOutput());
//            echo $output;
            Log::error('RFSniffer timeout exception: ' . $output);
            return $output;
        }
        catch (Exception $e) 
        {
            Log::error('RFSniffer execution exception: ' . $e->getMessage());
            return $e->getMessage();
        }
        
        Log::info('finished reading 433MHz');
        
    }
}


