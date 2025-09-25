<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use Illuminate\Support\Collection;
use RuntimeException;

class NewsDataService
{
    private string $latestUrl = 'https://newsdata.io/api/1/latest';
    private string $apiKey;

    public function __construct()
    {
        $this->apiKey = env('NEWSDATA_API_KEY');
    }

    /**
     * Recupera articoli per paese/lingua e salva nuove categorie nel DB
     */
    public function importCategoriesByCountry(string $countryCode, string $countryLanguage): Collection
    {
        $response = Http::get($this->latestUrl, [
            'apikey'   => $this->apiKey,
            'country'  => $countryCode,
            'language' => $countryLanguage,
        ]);

        if ($response->failed()) {
            throw new RuntimeException(
                "Errore API ({$response->status()}): ".$response->body()
            );
        }

        // Prendi tutti i valori 'category' dai risultati, unici e non null
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

    /**
     * Recupera notizie (paginazione) per i parametri passati
     */
    public function fetch(string $country, array $extra = [], int $pageNum = 1): array
    {
        // per la pagina 1 non serve token
        $query = array_merge($extra, ['apikey' => $this->apiKey]);

        if ($pageNum > 1) {
            $token = Cache::get("news_token:{$country}:{$pageNum}");
            if (! $token) {
                return ['status'=>'error','message'=>"Token per pagina {$pageNum} non trovato. Prima chiama le pagine precedenti."];
            }
            $query['page'] = $token;
        }

        $res = Http::get($this->latestUrl, $query);
        if ($res->failed()) {
            return ['status'=>'error','message'=>"Errore API ({$res->status()})"];
        }

        $data = $res->json();

        // memorizza il token per la pagina successiva
        if (!empty($data['nextPage'])) {
            $next = $pageNum + 1;
            Cache::put("news_token:{$country}:{$next}", $data['nextPage'], now()->addHours(1));
        }

        return $data;
    }
}
