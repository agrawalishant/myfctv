<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image');
            $table->enum('post_type', ['movie', 'series']);
            $table->unsignedBigInteger('movie_id')->nullable();
            $table->unsignedBigInteger('series_id')->nullable();
            $table->string('status');
            $table->timestamps();

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('series_id')->references('id')->on('series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
