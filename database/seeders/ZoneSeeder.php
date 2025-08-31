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
            'name' => 'Balkony Rechte Seite',
            'enabled' => '1',
            'has_watering' => '1',
            'rain_sensitive' => '1',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'not in use',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '1',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'SZ innen, no pump',
            'enabled' => '1',
            'has_watering' => '0',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Balkon Tisch',
            'enabled' => '1',
            'has_watering' => '1',
            'rain_sensitive' => '0',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'Bathroom A',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '1',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'SZ innen Palme',
            'enabled' => '1',
            'has_watering' => '1',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Bathroom C',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Bathroom D',
            'enabled' => '0',
            'has_watering' => '1',
            'rain_sensitive' => '0',
            'outdoor' => '0',
        ]);
    }
}
