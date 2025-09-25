<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\Category;
use App\Services\NewsDataService;

/**
 * ImportCategoriesCommand
 *
 * Artisan console command that fetches category data from the
 * NewsData API for every country stored in the database,
 * then associates those categories with each country.
 */
class ImportCategoriesCommand extends Command
{
    /**
     * The command name used to run it from the CLI.
     *
     * Usage: php artisan newsdata:import-categories
     */
    protected $signature = 'newsdata:import-categories';

    /**
     * A short description shown in `php artisan list`.
     */
    protected $description = 'Import NewsData categories for all countries in the database';

    /**
     * Execute the console command.
     *
     * @param  NewsDataService  $newsService  Service used to call the NewsData API.
     * @return int  Exit code (0 = success).
     */
    public function handle(NewsDataService $newsService)
    {
        $this->info('Starting category import...');

        // Loop through all countries with their related languages
        foreach (Country::with('languages')->get() as $country) {
            // Use the first language of the country as the request parameter (fallback to English)
            $language = $country->languages->first()->language ?? 'en';

            $this->info("Importing for {$country->code} ({$language})");

            try {
                // Retrieve unique categories from the NewsData API
                $categories = $newsService->importCategoriesByCountry($country->code, $language);

                // Attach the retrieved categories to the pivot table without removing existing ones
                $ids = Category::whereIn('name', $categories)->pluck('id');
                $country->categories()->syncWithoutDetaching($ids);

                $this->info("   -> Found categories: " . $categories->implode(', '));
            } catch (\Throwable $e) {
                // Log any error but continue with the next country
                $this->error("   -> Error: " . $e->getMessage());
            }
        }

        $this->info('Category import completed successfully.');
        return Command::SUCCESS;
    }
}
