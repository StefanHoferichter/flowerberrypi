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
            'zone_id' => '1',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '17',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => '433 Mhz Receiver für Brennenstuhl',
            'enabled' => '1',
            'sensor_type' => '2',
            'zone_id' => '1',
            'GPIO_IN' => '27',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Relay A',
            'enabled' => '1',
            'sensor_type' => '3',
            'zone_id' => '6',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '21',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Relay B',
            'enabled' => '1',
            'sensor_type' => '3',
            'zone_id' => '7',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '20',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Relay C',
            'enabled' => '1',
            'sensor_type' => '3',
            'zone_id' => '8',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '19',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Relay D',
            'enabled' => '1',
            'sensor_type' => '3',
            'zone_id' => '9',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '13',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'DHT11',
            'enabled' => '1',
            'sensor_type' => '4',
            'zone_id' => '1',
            'GPIO_IN' => '22',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'HC-SR04 Ultrasonic A',
            'enabled' => '1',
            'sensor_type' => '5',
            'zone_id' => '2',
            'GPIO_IN' => '26',
            'GPIO_OUT' => '16',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'HC-SR04 Ultrasonic B',
            'enabled' => '1',
            'sensor_type' => '5',
            'zone_id' => '3',
            'GPIO_IN' => '7',
            'GPIO_OUT' => '8',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'HC-SR04 Ultrasonic C',
            'enabled' => '0',
            'sensor_type' => '5',
            'zone_id' => '4',
            'GPIO_IN' => '9',
            'GPIO_OUT' => '10',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'HC-SR04 Ultrasonic D',
            'enabled' => '0',
            'sensor_type' => '5',
            'zone_id' => '5',
            'GPIO_IN' => '24',
            'GPIO_OUT' => '23',
            'GPIO_EXTRA' => '0',
        ]);
        DB::table('sensors')->insert([
            'name' => 'ADS1115 Moisture Sensor A Buntnessel',
            'enabled' => '1',
            'sensor_type' => '6',
            'zone_id' => '5',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '72',
        ]);
        DB::table('sensors')->insert([
            'name' => 'ADS1115 Moisture Sensor B Geranie',
            'enabled' => '0',
            'sensor_type' => '6',
            'zone_id' => '2',
            'GPIO_IN' => '1',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '72',
        ]);
        DB::table('sensors')->insert([
            'name' => 'ADS1115 Moisture Sensor C',
            'enabled' => '0',
            'sensor_type' => '6',
            'zone_id' => '3',
            'GPIO_IN' => '2',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '72',
        ]);
        DB::table('sensors')->insert([
            'name' => 'ADS1115 Moisture Sensor D',
            'enabled' => '0',
            'sensor_type' => '6',
            'zone_id' => '3',
            'GPIO_IN' => '3',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '72',
        ]);
        DB::table('sensors')->insert([
            'name' => 'ADS1115 Moisture Sensor E Hängepflanze',
            'enabled' => '1',
            'sensor_type' => '6',
            'zone_id' => '4',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '73',
        ]);
        DB::table('sensors')->insert([
            'name' => 'ADS1115 Moisture Sensor F Unkraut',
            'enabled' => '1',
            'sensor_type' => '6',
            'zone_id' => '4',
            'GPIO_IN' => '1',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '73',
        ]);
        DB::table('sensors')->insert([
            'name' => 'ADS1115 Moisture Sensor G trocken',
            'enabled' => '1',
            'sensor_type' => '6',
            'zone_id' => '4',
            'GPIO_IN' => '2',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '73',
        ]);
        DB::table('sensors')->insert([
            'name' => 'ADS1115 Moisture Sensor H',
            'enabled' => '0',
            'sensor_type' => '6',
            'zone_id' => '4',
            'GPIO_IN' => '3',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '73',
        ]);
        DB::table('sensors')->insert([
            'name' => 'Camera',
            'enabled' => '1',
            'sensor_type' => '7',
            'zone_id' => '1',
            'GPIO_IN' => '0',
            'GPIO_OUT' => '0',
            'GPIO_EXTRA' => '0',
        ]);
        
    }
}
