<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Language;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' =>'Belgium', 'code' => 'be', 'languages' => ['nl']],
            ['name' =>'Canada', 'code' => 'ca', 'languages' => ['en', 'fr']],
            ['name' =>'France', 'code' => 'fr', 'languages' => ['fr']],
            ['name' =>'Germany', 'code' => 'de', 'languages' => ['de']],
            ['name' =>'United Kingdom', 'code' => 'gb', 'languages' => ['en']],
        ];

        foreach ($countries as $data) {
            $country = Country::firstOrCreate([
                'name' => $data['name'],
                'code' => $data['code'],
            ]);

            foreach ($data['languages'] as $langCode) {
                $language = Language::firstOrCreate(['language' => $langCode]);
                $country->languages()->syncWithoutDetaching([$language->id]);
            }
        }
    }
}
