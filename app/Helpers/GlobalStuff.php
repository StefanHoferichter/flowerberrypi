<?php
namespace App\Helpers;


class GlobalStuff 
{
    
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
        if ($temp > 24)
            $classification=3;
        else if ($temp > 15)
            $classification=2;
        else
            $classification=1;
        
        return $classification;
    }
    

    public static function get_classification_from_soil_moisture($value)
    {
        if ($value < 1.7)
            $classification=1;
        else if ($value > 2.3)
            $classification=3;
        else
            $classification=2;
                    
        return $classification;
    }
    
                
}