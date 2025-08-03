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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();
            $table->String('name');
            $table->boolean('enabled')->default(true);
            $table->unsignedBigInteger('sensor_type');
            $table->foreign('sensor_type')->references('id')->on('sensor_types');
            $table->unsignedInteger('gpio_in');
            $table->unsignedInteger('gpio_out');
            $table->unsignedInteger('gpio_extra');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
