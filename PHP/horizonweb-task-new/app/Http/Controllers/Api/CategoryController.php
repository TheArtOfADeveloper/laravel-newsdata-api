<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NewsDataService;
use Illuminate\Http\JsonResponse;

/**
 * Handles importing news categories for a specific country and language
 * from the external NewsData.io API.
 */
class CategoryController extends Controller
{
    /**
     * Import and store categories for the given country and language.
     *
     * GET /api/categories/import/{country}/{language}
     *
     * This endpoint contacts the NewsData API to retrieve available
     * news categories for the specified country and language.
     * Any new categories are persisted in the database.
     *
     * @param  string           $countryCode  Two-letter country code (e.g. "ca").
     * @param  string           $language     Language code (e.g. "en").
     * @param  NewsDataService  $news         Service to interact with NewsData API.
     * @return \Illuminate\Http\JsonResponse  JSON response containing imported categories.
     */
    public function importByCountry(
        string $countryCode,
        string $language,
        NewsDataService $news
    ): JsonResponse {
        // Fetch categories from the NewsData API and store them in the database.
        $categories = $news->importCategoriesByCountry($countryCode, $language);

        // Return a structured JSON response.
        return response()->json([
            'status'     => 'success',
            'country'    => $countryCode,
            'language'   => $language,
            'categories' => $categories,
        ]);
    }
}
