<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cycles')->insert([
            'name' => 'All Cycles',
            'enabled' => '1',
            'has_watering' => '0',
        ]);
        DB::table('cycles')->insert([
            'name' => 'Balkony Left Side',
            'enabled' => '0',
            'has_watering' => '1',
        ]);
        DB::table('cycles')->insert([
            'name' => 'Balkony Right Side',
            'enabled' => '1',
            'has_watering' => '1',
        ]);
        DB::table('cycles')->insert([
            'name' => 'Balkony Table',
            'enabled' => '0',
            'has_watering' => '1',
        ]);
        DB::table('cycles')->insert([
            'name' => 'Balkony Bottom',
            'enabled' => '1',
            'has_watering' => '1',
        ]);
        DB::table('cycles')->insert([
            'name' => 'Bathroom A',
            'enabled' => '0',
            'has_watering' => '1',
        ]);
        DB::table('cycles')->insert([
            'name' => 'Bathroom B',
            'enabled' => '0',
            'has_watering' => '1',
        ]);
        DB::table('cycles')->insert([
            'name' => 'Bathroom C',
            'enabled' => '0',
            'has_watering' => '1',
        ]);
        DB::table('cycles')->insert([
            'name' => 'Bathroom D',
            'enabled' => '0',
            'has_watering' => '1',
        ]);
    }
}
