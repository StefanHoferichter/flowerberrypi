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
        Schema::create('watering_decisions', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->unsignedInteger('tod');
            $table->foreignId('zone_id')->constrained('zones');
            $table->unsignedInteger('forecast_classification');
            $table->unsignedInteger('humidity_classification');
            $table->unsignedInteger('watering_classification');
            $table->boolean('executed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watering_decisions');
    }
};
