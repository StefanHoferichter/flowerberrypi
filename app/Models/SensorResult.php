<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorResult extends Model
{
    // Keine zugeordnete Datenbank-Tabelle
    protected $table = null;
    
    // Deaktiviere automatische Timestamps
    public $timestamps = false;
    
    // Kein guarded / fillable nötig bei reinem PHP-Setzen
    
    // Überschreibe das Verhalten, um kein Query-Builder-Verhalten auszulösen
    public function getTable()
    {
        return null;
    }
    
    // Initialwerte können direkt gesetzt werden
    public $value;
    public $name;
    public $zone_name;
    public $sensor_id;
    public $error;
    
}
