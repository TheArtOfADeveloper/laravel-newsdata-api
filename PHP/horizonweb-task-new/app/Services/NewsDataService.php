<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Service class responsible for interacting with the external NewsData.io API.
 * It provides methods to:
 *  - Fetch and import news categories for a specific country/language.
 *  - Retrieve paginated news articles while caching the next-page tokens.
 */
class NewsDataService
{
    /**
     * Base endpoint for the NewsData.io "latest" API.
     */
    private string $latestUrl = 'https://newsdata.io/api/1/latest';

    /**
     * API key loaded from the environment configuration.
     */
    private string $apiKey;

    /**
     * Constructor: loads the NewsData API key from the .env file.
     */
    public function __construct()
    {
        $this->apiKey = env('NEWSDATA_API_KEY');
    }

    /**
     * Fetches news articles for a given country and language from the external API
     * and stores any unique categories found in the database.
     *
     * @param  string  $countryCode      Two-letter country code (e.g., "ca").
     * @param  string  $countryLanguage  Language code for filtering news (e.g., "en").
     * @return \Illuminate\Support\Collection  A collection of unique category names.
     *
     * @throws \RuntimeException If the external API request fails.
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
                "API request failed ({$response->status()}): " . $response->body()
            );
        }

        // Extract the 'category' field from each result, remove nulls, and keep unique values.
        $categories = collect($response->json('results') ?? [])
            ->pluck('category')
            ->flatten()
            ->filter()
            ->unique();

        // Store any new categories in the database if they do not already exist.
        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }

        return $categories->values();
    }

    /**
     * Retrieves paginated news articles for a given country, with optional extra query parameters.
     * Uses caching to store and reuse the "nextPage" token required by the NewsData.io API
     * for subsequent pages.
     *
     * @param  string  $country   Country code for caching the nextPage token.
     * @param  array   $extra     Additional query parameters (language, category, etc.).
     * @param  int     $pageNum   Page number to fetch. Page 1 does not require a token.
     * @return array              The decoded JSON response from the API.
     */
    public function fetch(string $country, array $extra = [], int $pageNum = 1): array
    {
        // Build the query with the API key and any extra filters.
        $query = array_merge($extra, ['apikey' => $this->apiKey]);

        // If requesting a page beyond the first, retrieve the cached nextPage token.
        if ($pageNum > 1) {
            $token = Cache::get("news_token:{$country}:{$pageNum}");
            if (! $token) {
                return [
                    'status'  => 'error',
                    'message' => "Token for page {$pageNum} not found. Call the previous page first."
                ];
            }
            $query['page'] = $token;
        }

        $res = Http::get($this->latestUrl, $query);
        if ($res->failed()) {
            return [
                'status'  => 'error',
                'message' => "API request failed ({$res->status()})"
            ];
        }

        $data = $res->json();

        // Cache the nextPage token to allow retrieval of the following page.
        if (!empty($data['nextPage'])) {
            $next = $pageNum + 1;
            Cache::put("news_token:{$country}:{$next}", $data['nextPage'], now()->addHours(1));
        }

        return $data;
    }
}
