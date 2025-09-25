<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    /**
     * Rimuove una categoria da un paese cercandola per nome
     */
    public function removeCategoryByName(string $code, string $categoryName): JsonResponse
    {
        // Trova il paese
        $country = Country::where('code', $code)->firstOrFail();

        // Trova la categoria in base al nome
        $category = Category::where('name', $categoryName)->first();

        if (! $category) {
            return response()->json([
                'status'  => 'error',
                'message' => "Categoria '{$categoryName}' non trovata."
            ], 404);
        }

        // Rimuove il collegamento nella tabella pivot
        $country->categories()->detach($category->id);

        return response()->json([
            'status'  => 'success',
            'message' => "Categoria '{$categoryName}' rimossa da {$country->name}"
        ]);
    }
}
