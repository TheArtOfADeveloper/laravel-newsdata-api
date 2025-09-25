<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Services\NewsDataService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handles API endpoints for retrieving news articles from the external
 * NewsData.io service. Supports fetching news by category or as a
 * paginated feed for a given country.
 */
class NewsController extends Controller
{
    /**
     * The service responsible for communicating with the NewsData API.
     *
     * @var NewsDataService
     */
    private NewsDataService $service;

    /**
     * Inject the NewsDataService dependency.
     *
     * @param  NewsDataService  $service
     */
    public function __construct(NewsDataService $service)
    {
        $this->service = $service;
    }

    /**
     * Fetch news articles for a specific country and category.
     *
     * GET /country/{code}/{category}
     *
     * @param  string  $code      Two-letter country code (e.g. "ca").
     * @param  string  $category  Category name to filter news (e.g. "sports").
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory(string $code, string $category)
    {
        // Find the country and eager-load its languages.
        $country   = Country::where('code', $code)->with('languages')->firstOrFail();

        // Build a comma-separated list of language codes (e.g. "en,fr").
        $languages = $country->languages->pluck('code')->implode(',');

        // Call the NewsData API through the service.
        $data = $this->service->fetch(
            $code, // country code string
            [
                'country'  => $code,
                'language' => $languages,
                'category' => $category,
            ]
        );

        return response()->json($data, Response::HTTP_OK);
    }

    /**
     * Fetch a paginated list of news articles for a country.
     * The service internally handles the NewsData "nextPage" tokens.
     *
     * GET /news/{country}/{page?}
     *
     * @param  string  $country  Two-letter country code (e.g. "ca").
     * @param  int     $page     Page number to fetch (defaults to 1).
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginated(string $country, int $page = 1)
    {
        // Retrieve the country with related languages and categories.
        $c = Country::where('code', $country)
            ->with(['languages','categories'])
            ->firstOrFail();

        // Prepare comma-separated strings for languages and categories.
        $languages  = $c->languages->pluck('code')->implode(',');
        $categories = $c->categories->pluck('name')->implode(',');

        // Request news data with pagination.
        $data = $this->service->fetch(
            $country, // country code
            [
                'country'  => $country,
                'language' => $languages,
                'category' => $categories,
            ],
            $page      // numeric page index
        );

        return response()->json($data, Response::HTTP_OK);
    }
}
