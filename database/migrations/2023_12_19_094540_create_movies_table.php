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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('movie_categories')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('access');
            $table->string('content_rating');
            $table->date('release_date');
            $table->integer('duration'); // in minutes
            $table->string('thumbnail');
            $table->string('poster');
            $table->string('trailer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
