<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = ['nl', 'fr','en', 'de'];

        foreach ($languages as $lang) {
            Language::firstOrCreate(['language' => $lang]);
        }
    }
}
