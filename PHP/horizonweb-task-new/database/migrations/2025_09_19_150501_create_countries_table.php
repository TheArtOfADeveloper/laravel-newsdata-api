<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create the "countries" table to store country information.
     * Each record contains a unique country name and a 2-letter ISO code.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();                               // Primary key (auto-increment)
            $table->string('name')->unique();           // Country name, must be unique
            $table->string('code', 2)->unique();        // 2-letter ISO country code, unique
            $table->timestamps();                       // created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drop the "countries" table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
