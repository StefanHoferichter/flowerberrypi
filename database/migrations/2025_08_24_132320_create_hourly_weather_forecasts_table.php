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
        Schema::create('hourly_weather_forecasts', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->unsignedInteger('hour');
            $table->double('temperature', $precision = 8, $scale = 2);
            $table->double('precipitation', $precision = 8, $scale = 2);
            $table->double('cloud_cover', $precision = 8, $scale = 2);
            $table->unsignedInteger('classification');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hourly_weather_forecasts');
    }
};
