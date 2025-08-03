<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sensors')->insert([
            'name' => '433 Mhz Sender für Brennenstuhl',
            'enabled' => '1',
            'sensor_type' => '1',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '17',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => '433 Mhz Receiver für Brennenstuhl',
            'enabled' => '1',
            'sensor_type' => '1',
            'GPIO_IN' => '27',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Relay A',
            'enabled' => '1',
            'sensor_type' => '3',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '21',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Relay B',
            'enabled' => '1',
            'sensor_type' => '3',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '20',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Relay C',
            'enabled' => '1',
            'sensor_type' => '3',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '19',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Relay D',
            'enabled' => '1',
            'sensor_type' => '3',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '13',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'DHT11',
            'enabled' => '1',
            'sensor_type' => '4',
            'GPIO_IN' => '22',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '0',
        ]);
        
    }
}
