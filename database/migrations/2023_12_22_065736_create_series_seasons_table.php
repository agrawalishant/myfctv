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
        Schema::create('series_seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained(); // Foreign key to link to the series table
            $table->string('series_name');
            $table->string('season_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_seasons');
    }
};
