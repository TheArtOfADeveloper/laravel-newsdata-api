<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NewsDataService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function importByCountry(
        string $countryCode,
        string $language,
        NewsDataService $news
    ): JsonResponse {
        $categories = $news->importCategoriesByCountry($countryCode, $language);

        return response()->json([
            'status'     => 'success',
            'country'    => $countryCode,
            'language'   => $language,
            'categories' => $categories,
        ]);
    }
}
