<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SensorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('sensor_types')->insert([
            'name' => '433 Mhz Sender',
            'order_val' => '10',
        ]);
        DB::table('sensor_types')->insert([
            'name' => '433 Mhz Receiver',
            'order_val' => '20',
        ]);
        DB::table('sensor_types')->insert([
            'name' => '4 Relay Module',
            'order_val' => '30',
        ]);
        DB::table('sensor_types')->insert([
            'name' => 'DHT11 Temperature Sensor',
            'order_val' => '40',
        ]);
        
    }
}
