<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Language;

class CountrySeeder extends Seeder
{
    /**
     * Seed the database with a set of predefined countries and their languages.
     *
     * This seeder inserts a list of countries with their ISO code
     * and attaches one or more language records to each country.
     * If a country or language already exists, it will not be duplicated.
     */
    public function run(): void
    {
        // Static dataset of countries with their ISO code and supported languages
        $countries = [
            ['name' => 'Belgium',         'code' => 'be', 'languages' => ['nl']],
            ['name' => 'Canada',          'code' => 'ca', 'languages' => ['en', 'fr']],
            ['name' => 'France',          'code' => 'fr', 'languages' => ['fr']],
            ['name' => 'Germany',         'code' => 'de', 'languages' => ['de']],
            ['name' => 'United Kingdom',  'code' => 'gb', 'languages' => ['en']],
        ];

        foreach ($countries as $data) {
            // Create the country if it does not already exist
            $country = Country::firstOrCreate([
                'name' => $data['name'],
                'code' => $data['code'],
            ]);

            // For each language code, create the language if needed
            // and attach it to the country without removing previous relations
            foreach ($data['languages'] as $langCode) {
                $language = Language::firstOrCreate(['code' => $langCode]);
                $country->languages()->syncWithoutDetaching([$language->id]);
            }
        }
    }
}
