<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualWateringDecision extends Model
{
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
