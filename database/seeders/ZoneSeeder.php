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
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Balkony Rechte Seite',
            'enabled' => '0',
            'has_watering' => '1',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'Balkony Right Side',
            'enabled' => '0',
            'has_watering' => '1',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'SZ innen',
            'enabled' => '1',
            'has_watering' => '1',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Balkon Tisch',
            'enabled' => '1',
            'has_watering' => '1',
            'outdoor' => '1',
        ]);
        DB::table('zones')->insert([
            'name' => 'Bathroom A',
            'enabled' => '0',
            'has_watering' => '1',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Bathroom B',
            'enabled' => '1',
            'has_watering' => '1',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Bathroom C',
            'enabled' => '0',
            'has_watering' => '1',
            'outdoor' => '0',
        ]);
        DB::table('zones')->insert([
            'name' => 'Bathroom D',
            'enabled' => '0',
            'has_watering' => '1',
            'outdoor' => '0',
        ]);
    }
}
