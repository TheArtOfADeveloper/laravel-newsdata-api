<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Country;
use App\Models\Category;
use App\Services\NewsDataService;

class ImportCategoriesCommand extends Command
{
    /**
     * Il nome per lanciare il comando
     */
    protected $signature = 'newsdata:import-categories';

    protected $description = 'Importa le categorie di NewsData per tutti i paesi presenti nel DB';

    public function handle(NewsDataService $newsService)
    {
        $this->info('Inizio import categorie...');

        foreach (Country::with('languages')->get() as $country) {
            // usa la prima lingua del paese per la chiamata
            $language = $country->languages->first()->language ?? 'en';

            $this->info("Import per {$country->code} ({$language})");

            try {
                $categories = $newsService->importCategoriesByCountry($country->code, $language);

                // Collega le categorie al paese nella pivot
                $ids = Category::whereIn('name', $categories)->pluck('id');
                $country->categories()->syncWithoutDetaching($ids);

                $this->info("   -> trovate: " . $categories->implode(', '));
            } catch (\Throwable $e) {
                $this->error("   -> errore: " . $e->getMessage());
            }
        }

        $this->info('Import completato!');
        return Command::SUCCESS;
    }
}
