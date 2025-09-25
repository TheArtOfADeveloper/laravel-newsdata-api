<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Category;
use Illuminate\Support\Collection;

class NewsDataService
{
    // Se "latest" è parte dell'endpoint, va nel path
    private string $baseUrl = 'https://newsdata.io/api/1/latest';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('NEWSDATA_API_KEY');
    }

    /**
     * Recupera articoli per paese/lingua e inserisce le categorie trovate
     */
    public function importCategoriesByCountry(string $countryCode, string $countryLanguage): Collection
    {
        $response = Http::get($this->baseUrl, [
            'apikey'   => $this->apiKey,
            'country'  => $countryCode,
            'language' => $countryLanguage
        ]);

        if ($response->failed()) {
            throw new \RuntimeException(
                "Errore API ({$response->status()}): ".$response->body()
            );
        }

        $categories = collect($response->json('results') ?? [])
            ->pluck('category')
            ->flatten()
            ->filter()
            ->unique();

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }

        return $categories->values();
    }
}
