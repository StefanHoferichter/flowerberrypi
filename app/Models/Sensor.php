<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
    public function type()
    {
        return $this->belongsTo(SensorType::class, 'sensor_type');
    }
}
