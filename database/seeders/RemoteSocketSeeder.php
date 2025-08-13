<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoteSocketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('remote_sockets')->insert([
            'name' => 'Brennenstuhl A',
            'code_on' => '1312081',
            'code_off' => '1312084',
            'cycle_id' => '2',
        ]);
        DB::table('remote_sockets')->insert([
            'name' => 'Brennenstuhl B',
            'code_on' => '1315153',
            'code_off' => '1315156',
            'cycle_id' => '3',
        ]);
        DB::table('remote_sockets')->insert([
            'name' => 'Brennenstuhl C',
            'code_on' => '1315921',
            'code_off' => '1315924',
            'cycle_id' => '4',
        ]);
        DB::table('remote_sockets')->insert([
            'name' => 'Brennenstuhl D',
            'code_on' => '1316113',
            'code_off' => '1316116',
            'cycle_id' => '5',
        ]);
    }
}
