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
        Schema::create('coming_soons', function (Blueprint $table) {
            $table->id();
            $table->string('thumbnail');
            $table->string('poster');
            $table->string('title');
            $table->string('local')->nullable(); // You might want to store the video URL
            $table->string('youtube')->nullable(); // You might want to store the video URL           
            $table->text('description');            
            $table->year('publish_year');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coming_soons');
    }
};
