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
        Schema::create('series_season_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_seasons_id')->constrained(); // Foreign key to link to the seasons table
            $table->string('title');
            $table->string('local')->nullable(); // You might want to store the video URL
            $table->string('youtube')->nullable(); // You might want to store the video URL
            $table->string('poster');
            $table->time('duration');
            $table->string('video_quality');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series_season_episodes');
    }
};
