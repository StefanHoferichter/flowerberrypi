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
        Schema::create('percentage_conversions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('sensor_id');
            $table->double('lower_limit', $precision = 8, $scale = 2);
            $table->double('upper_limit', $precision = 8, $scale = 2);
            $table->unsignedInteger('invert');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('percentage_conversions');
    }
};
