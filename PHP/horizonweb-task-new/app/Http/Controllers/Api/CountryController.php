<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\CountryResource;   


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

    // index()
public function index()
{
    $countries = Cache::remember('countries.all', 3600, function () {
        return Country::with(['languages', 'categories'])->get();
    });

    return response()->json([
        'countries' => $countries,
    ], 200);
}

// show()
public function show(string $code)
{
    $country = Country::where('code', $code)
        ->with(['languages', 'categories'])
        ->firstOrFail();

    return new CountryResource($country);
}



}
