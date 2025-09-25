<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CountryResource;

/**
 * Handles API endpoints related to Country resources.
 * Provides endpoints to list countries, show a single country,
 * and detach (remove) a category from a specific country.
 */
class CountryController extends Controller
{
    /**
     * Remove a specific category from a given country by its code and category name.
     *
     * @param  string  $code         Two-letter country code (e.g. "ca").
     * @param  string  $categoryName Name of the category to remove (e.g. "sports").
     * @return JsonResponse          JSON response indicating success or error.
     */
    public function removeCategoryByName(string $code, string $categoryName): JsonResponse
    {
        // Find the country by its code, or fail with 404 if it does not exist.
        $country = Country::where('code', $code)->firstOrFail();

        // Find the category by its name.
        $category = Category::where('name', $categoryName)->first();

        // If category is not found, return a 404 JSON error.
        if (! $category) {
            return response()->json([
                'status'  => 'error',
                'message' => "Category '{$categoryName}' not found."
            ], Response::HTTP_NOT_FOUND);
        }

        // Detach the relationship between the country and the category
        // in the pivot table (country_category).
        $country->categories()->detach($category->id);

        return response()->json([
            'status'  => 'success',
            'message' => "Category '{$categoryName}' removed from {$country->name}."
        ], Response::HTTP_OK);
    }

    /**
     * Return a list of all countries with their languages and categories.
     * Results are cached for one hour to reduce database queries.
     *
     * GET /countries
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // Cache key: 'countries.all', expires in 3600 seconds (1 hour)
        $countries = Cache::remember('countries.all', 3600, function () {
            return Country::with(['languages', 'categories'])->get();
        });

        return response()->json([
            'countries' => $countries,
        ], Response::HTTP_OK);
    }

    /**
     * Return details of a single country by its code,
     * including its related languages and categories.
     *
     * GET /country/{code}
     *
     * @param  string  $code  Two-letter country code (e.g. "ca").
     * @return CountryResource
     */
    public function show(string $code): CountryResource
    {
        $country = Country::where('code', $code)
            ->with(['languages', 'categories'])
            ->firstOrFail();

        // Use a dedicated API resource to format the JSON output.
        return new CountryResource($country);
    }
}
