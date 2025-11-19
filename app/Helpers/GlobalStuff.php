<?php
namespace App\Helpers;

use App\Models\Threshold;
use Illuminate\Support\Facades\Cache;


class GlobalStuff 
{
    protected static function getAllThresholds(): array
    {
        return Cache::remember('thresholds_all', now()->addHours(1), function () {
            return Threshold::all()->keyBy('type')->toArray();
        });
    }
    public static function get_temperature_color()
    {
        return '#E41A1C'; //rot
    }
    public static function get_precipitation_color()
    {
        return '#377EB8'; //blau
    }
    public static function get_cloud_cover_color()
    {
        return '#66C2A5'; //türkis
    }
    public static function get_water_level_color()
    {
        return '#984EA3'; //violett
    }
    public static function get_watering_color()
    {
        return '#8DA0CB'; //lavendel
    }
    public static function get_manual_watering_color()
    {
        return '#F781BF'; //pink
    }
    public static function get_soil_moisture_color($index)
    {
        $colorPalette = [
            '#4DAF4A', // grün =  moist1
            '#FF7F00', // orange  = moist2
            '#FFFF33', // gelb    = moist3
            '#A65628', // braun   = moist4
            '#999999', // grau
            '#FC8D62', // lachs
            '#A6CEE3', // hellblau (NEU)
            '#1F78B4'  // kräftiges blau (NEU)
        ];
        
        return $colorPalette[$index];
    }
    
    public static function get_tod_from_hour($hour)
    {
        $tod = 0;
        if ($hour >= 9 and $hour < 13)
        {
            $tod=1;
        }
        if ($hour >=13 and $hour < 17)
        {
            $tod=2;
        }
        if ($hour >=17)
        {
            $tod=3;
        }
        
        return $tod;
    }
    public static function is_first_hour_of_tod($hour)
    {
        $ifh = 0;
        if ($hour == 9)
        {
            $ifh=1;
        }
        if ($hour == 13 )
        {
            $ifh=1;
        }
        if ($hour == 17)
        {
            $ifh=1;
        }
        
        return $ifh;
    }
    
    public static function get_classification_from_temperature($temp)
    {
        $classification=0;
        if ($temp > GlobalStuff::get_temperature_threshold_high())
            $classification=3;
        else if ($temp > GlobalStuff::get_temperature_threshold_low())
            $classification=2;
        else
            $classification=1;
        
        return $classification;
    }

    public static function get_temperature_threshold_low()
    {
        $thresholds = self::getAllThresholds();
        
        return $thresholds[1]['lower_limit'] ?? null;
//        return 15;
    }
    public static function get_temperature_threshold_high()
    {
        $thresholds = self::getAllThresholds();
        
        return $thresholds[1]['upper_limit'] ?? null;
//        return 24;
    }
    

    public static function get_classification_from_soil_moisture($value)
    {
        if ($value < GlobalStuff::get_soil_moisture_threshold_low())
            $classification=3;
        else if ($value > GlobalStuff::get_soil_moisture_threshold_high())
            $classification=1;
        else
            $classification=2;
                    
        return $classification;
    }
    
    public static function get_soil_moisture_threshold_low()
    {
        $thresholds = self::getAllThresholds();
            
        return $thresholds[4]['lower_limit'] ?? null;
//        return 40.0;
    }
    public static function get_soil_moisture_threshold_high()
    {
//        Cache::forget('thresholds_all');
        $thresholds = self::getAllThresholds();
        
        return $thresholds[4]['upper_limit'] ?? null;
//        return 65.0;
    }
    
    public static function get_classification_from_tank($value)
    {
        $classification=0;
        
        if ($value < GlobalStuff::get_tank_threshold_low())
            $classification=3;
            else if ($value < GlobalStuff::get_tank_threshold_high())
            $classification=2;
        else
            $classification=1;
                    
                    
        return $classification;
    }

    public static function get_tank_threshold_low()
    {
        $thresholds = self::getAllThresholds();
        
        return $thresholds[3]['lower_limit'] ?? null;
//        return 10.0;
    }
    public static function get_tank_threshold_high()
    {
        $thresholds = self::getAllThresholds();
        
        return $thresholds[3]['upper_limit'] ?? null;
        
//        return 20.0;
    }
    
    
    public static function get_url_from_sensor_type($sensor_type)
    {
        if ($sensor_type == 1)
        {
            $action = '/remote_sockets';
        }
        else if ($sensor_type == 3)
        {
            $action = '/relays';
        }
        else if ($sensor_type == 4)
        {
            $action = '/temperatures';
        }
        else if ($sensor_type == 5)
        {
            $action = '/distances';
        }
        else if ($sensor_type == 6)
        {
            $action = '/soil_moistures';
        }
        else if ($sensor_type == 7)
        {
            $action = '/camera';
        }
        else
        {
            $action = '/';
        }
        
        return $action;
    }
    
    
    public static function isRaspberryPi5(): bool 
    {
        $modelFile = '/proc/device-tree/model';
        
        if (!file_exists($modelFile)) 
        {
            return false; // kein Raspberry Pi
        }
        
        $model = trim(file_get_contents($modelFile));

        return stripos($model, 'Raspberry Pi 5') !== false;
    }
    
}