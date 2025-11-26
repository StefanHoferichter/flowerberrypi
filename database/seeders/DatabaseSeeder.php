<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin',
            'password' => bcrypt('flowerberry5()PI'),
        ]);
        
        $this->call([
            ZoneSeeder::class,
        ]);
        $this->call([
            SensorTypeSeeder::class,
        ]);
        $this->call([
            SensorValueTypeSeeder::class,
        ]);
        $this->call([
            SensorSeeder::class,
        ]);
        $this->call([
            RemoteSocketSeeder::class,
        ]);
        $this->call([
            WiFiSocketSeeder::class,
        ]);
        $this->call([
            PercentageConversionSeeder::class,
        ]);
        $this->call([
            ThresholdSeeder::class,
        ]);
        $this->call([
            LocationSeeder::class,
        ]);
    }
}
