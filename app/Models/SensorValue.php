<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorValue extends Model
{
    public function sensor_value_type()
    {
        return $this->belongsTo(SensorValueType::class, 'type');
    }
    
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }
    
}
