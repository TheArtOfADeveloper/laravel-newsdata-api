<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create the "categories" table to store all available news categories.
     * Each category has a unique name and automatic timestamps.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();                                // Primary key (auto–increment)
            $table->string('name')->unique();            // Category name, must be unique
            $table->timestamps();                        // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drop the "categories" table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
