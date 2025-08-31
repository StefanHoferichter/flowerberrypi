<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PercentageConversionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '8',
            'lower_limit' => '2',
            'upper_limit' => '25',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '9',
            'lower_limit' => '2',
            'upper_limit' => '25',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '10',
            'lower_limit' => '2',
            'upper_limit' => '25',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '11',
            'lower_limit' => '2',
            'upper_limit' => '25',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '12',
            'lower_limit' => '1.1',
            'upper_limit' => '2.9',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '13',
            'lower_limit' => '1.1',
            'upper_limit' => '2.9',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '14',
            'lower_limit' => '1.1',
            'upper_limit' => '2.9',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '15',
            'lower_limit' => '1.1',
            'upper_limit' => '2.9',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '16',
            'lower_limit' => '1.1',
            'upper_limit' => '2.9',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '17',
            'lower_limit' => '1.1',
            'upper_limit' => '2.9',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '18',
            'lower_limit' => '1.1',
            'upper_limit' => '2.9',
            'invert' => '1',
        ]);
        DB::table('percentage_conversions')->insert([
            'sensor_id' => '19',
            'lower_limit' => '1.1',
            'upper_limit' => '2.9',
            'invert' => '1',
        ]);
    }
}
