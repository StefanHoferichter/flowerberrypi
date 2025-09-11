<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ThresholdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('thresholds')->insert([
            'type' => '1',
            'lower_limit' => '15',
            'upper_limit' => '24',
        ]);
        DB::table('thresholds')->insert([
            'type' => '3',
            'lower_limit' => '20',
            'upper_limit' => '40',
        ]);
        DB::table('thresholds')->insert([
            'type' => '4',
            'lower_limit' => '45',
            'upper_limit' => '65',
        ]);
    }
}
