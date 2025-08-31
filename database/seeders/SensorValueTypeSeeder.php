<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SensorValueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sensor_value_types')->insert([
            'name' => 'Temperature',
            'order_val' => '10',
        ]);
        DB::table('sensor_value_types')->insert([
            'name' => 'Humidity',
            'order_val' => '20',
        ]);
        DB::table('sensor_value_types')->insert([
            'name' => 'Tank Remaining',
            'order_val' => '30',
        ]);
        DB::table('sensor_value_types')->insert([
            'name' => 'Soil Moisture',
            'order_val' => '30',
        ]);
        
    }
}
