<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Threshold extends Model
{
    public function sensor_value_type()
    {
        return $this->belongsTo(SensorValueType::class, 'type');
    }
}
