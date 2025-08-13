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
        Schema::create('remote_sockets', function (Blueprint $table) {
            $table->id();
            $table->String('name');
            $table->unsignedInteger('code_on');
            $table->unsignedInteger('code_off');
            $table->foreignId('cycle_id')->constrained('cycles');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remote_sockets');
    }
};
