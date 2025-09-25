<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create the pivot table `country_language` to represent
     * the many-to-many relationship between countries and languages.
     */
    public function up(): void
    {
        Schema::create('country_language', function (Blueprint $table) {
            // Foreign key referencing the countries table
            $table->unsignedBigInteger('country_id');

            // Foreign key referencing the languages table
            $table->unsignedBigInteger('language_id');

            // Composite primary key to guarantee that each
            // country-language pair is unique
            $table->primary(['country_id', 'language_id']);

            // Cascade deletes: if a country or a language is removed,
            // all related records in this pivot table are deleted
            $table->foreign('country_id')
                  ->references('id')->on('countries')
                  ->onDelete('cascade');

            $table->foreign('language_id')
                  ->references('id')->on('languages')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drop the `country_language` table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_language');
    }
};
