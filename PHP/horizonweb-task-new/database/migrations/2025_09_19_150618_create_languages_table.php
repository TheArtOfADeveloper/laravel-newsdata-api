<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create the "languages" table to store ISO language codes.
     * Each record has a 2-character unique code and automatic timestamps.
     */
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();                                // Primary key (auto-increment)
            $table->string('code', 2)->unique();         // 2-letter language code, unique
            $table->timestamps();                        // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drop the "languages" table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
