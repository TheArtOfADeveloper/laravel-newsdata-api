<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Seed the database with a predefined set of languages.
     *
     * This seeder inserts a fixed list of language codes
     * into the "languages" table. If a language already exists,
     * it will not be duplicated.
     */
    public function run(): void
    {
        // Array of ISO language codes to insert
        $languages = ['nl', 'fr', 'en', 'de'];

        // Create each language if it does not already exist
        foreach ($languages as $lang) {
            Language::firstOrCreate(['code' => $lang]);
        }
    }
}
