<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Create the pivot table `country_category` to represent
     * the many-to-many relationship between countries and categories.
     */
    public function up(): void
    {
        Schema::create('country_category', function (Blueprint $table) {
            // Foreign key to the countries table
            $table->unsignedBigInteger('country_id');

            // Foreign key to the categories table
            $table->unsignedBigInteger('category_id');

            // Composite primary key ensures unique country/category pairs
            $table->primary(['country_id', 'category_id']);

            // Cascade deletes: if a country or category is removed,
            // its relationships are automatically deleted
            $table->foreign('country_id')
                  ->references('id')->on('countries')
                  ->onDelete('cascade');

            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drop the `country_category` table if it exists.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_category');
    }
};
