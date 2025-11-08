<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('zones')->insert([
            'name' => 'All Zones',
            'enabled' => '1',
            'has_watering' => '0',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Zone A',
            'enabled' => '1',
            'has_watering' => '1',
            'rain_sensitive' => '1',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'Zone B',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '1',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'Zone C',
            'enabled' => '0',
            'has_watering' => '0',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Zone D',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '0',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'Zone E',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '1',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Zone F',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Zone G',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Zone H',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
    }
}
