<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WiFiSocket extends Model
{
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
