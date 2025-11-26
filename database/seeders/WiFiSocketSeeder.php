<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WiFiSocketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('wi_fi_sockets')->insert([
            'name' => 'Shelly A',
            'url_on' => 'http://192.168.1.52/rpc/Switch.Set?id=0&on=true',
            'url_off' => 'http://192.168.1.52/rpc/Switch.Set?id=0&on=false',
            'zone_id' => '2',
        ]);
        DB::table('wi_fi_sockets')->insert([
            'name' => 'Shelly B',
            'url_on' => 'http://192.168.1.x/rpc/Switch.Set?id=0&on=true',
            'url_off' => 'http://192.168.1.x/rpc/Switch.Set?id=0&on=false',
            'zone_id' => '3',
        ]);
        DB::table('wi_fi_sockets')->insert([
            'name' => 'Shelly C',
            'url_on' => 'http://192.168.1.x/rpc/Switch.Set?id=0&on=true',
            'url_off' => 'http://192.168.1.x/rpc/Switch.Set?id=0&on=false',
            'zone_id' => '4',
        ]);
        DB::table('wi_fi_sockets')->insert([
            'name' => 'Shelly D',
            'url_on' => 'http://192.168.1.x/rpc/Switch.Set?id=0&on=true',
            'url_off' => 'http://192.168.1.x/rpc/Switch.Set?id=0&on=false',
            'zone_id' => '5',
        ]);}
}
