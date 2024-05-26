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
        Schema::create('movie_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name');
            $table->text('description')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->enum('access_control', ['public', 'restricted'])->default('public');
            $table->integer('age_restriction')->nullable();
            $table->boolean('featured_category')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movie_categories');
    }
};
