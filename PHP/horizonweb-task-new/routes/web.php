<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\NewsController;

use App\Http\Middleware\VerifyCsrfToken;


Route::get('/', function () {
    return view('welcome');
});


Route::get('api/categories/import/{country}/{language}', [CategoryController::class, 'importByCountry']);


Route::get('/start-session', function () {
    session()->put('key', 'value');
    return response()->json(['token' => csrf_token()]);
});


Route::get('/countries', [CountryController::class, 'index']);
Route::get('/country/{code}', [CountryController::class, 'show']);
Route::delete('/country/{code}/category/{categoryName}', [CountryController::class, 'removeCategoryByName']);
Route::get('news/{country}/{page?}', [NewsController::class, 'paginated']);
Route::get('country/{code}/{category}', [NewsController::class, 'byCategory']);




