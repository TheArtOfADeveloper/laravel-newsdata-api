<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Middleware\VerifyCsrfToken;

/**
 * Web & API Routes
 *
 * This file defines all HTTP routes for the application,
 * including API endpoints for countries, categories, and news.
 */

// Root route: returns the default welcome view.
Route::get('/', function () {
    return view('welcome');
});

/**
 * Import categories from the NewsData API for a specific country and language.
 * Example: GET /api/categories/import/ca/en
 */
Route::get('api/categories/import/{country}/{language}', [CategoryController::class, 'importByCountry']);

/**
 * Utility route to start a session and retrieve a CSRF token.
 * Useful for testing session-based requests.
 * Example: GET /start-session
 */
Route::get('/start-session', function () {
    session()->put('key', 'value');
    return response()->json(['token' => csrf_token()]);
});

/**
 * Country-related endpoints
 *
 * GET /countries                -> Return all countries with languages and categories
 * GET /country/{code}           -> Return details of a single country by code
 * DELETE /country/{code}/category/{categoryName}
 *                               -> Remove a category from a specific country
 */
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/country/{code}', [CountryController::class, 'show']);
Route::delete('/country/{code}/category/{categoryName}', [CountryController::class, 'removeCategoryByName']);

/**
 * News-related endpoints
 *
 * GET /news/{country}/{page?}   -> Paginated news articles for a given country,
 *                                  using categories and languages from the database.
 *                                  The {page} parameter is optional.
 *
 * GET /country/{code}/{category} -> News articles for a specific country and category.
 */
Route::get('news/{country}/{page?}', [NewsController::class, 'paginated']);
Route::get('country/{code}/{category}', [NewsController::class, 'byCategory']);
