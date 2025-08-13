<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public function cycle()
    {
        return $this->belongsTo(Cycle::class);
    }
}
