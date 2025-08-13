<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('weather_forecasts', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->double('min_temp', $precision = 8, $scale = 2);
            $table->double('max_temp', $precision = 8, $scale = 2);
            $table->double('sunshine_duration');
            $table->double('rain_sum', $precision = 8, $scale = 2);
            $table->unsignedInteger('classification');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_forecasts');
    }
};
