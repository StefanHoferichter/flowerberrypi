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
        Schema::create('sensor_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_id');
            $table->unsignedBigInteger('sensor_id');
            $table->foreign('sensor_id')->references('id')->on('sensors');
            $table->date('day');
            $table->unsignedInteger('hour');
            $table->unsignedBigInteger('type');
            $table->foreign('type')->references('id')->on('sensor_value_types');
            $table->double('value', $precision = 8, $scale = 2);
            $table->unsignedInteger('classification');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_values');
    }
};
