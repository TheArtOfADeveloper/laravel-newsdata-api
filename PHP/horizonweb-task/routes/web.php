<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;

use App\Http\Middleware\VerifyCsrfToken;


Route::get('/', function () {
    return view('welcome');
});


Route::get('api/categories/import/{country}/{language}', [CategoryController::class, 'importByCountry']);
Route::delete('api/country/{code}/category/{categoryName}', function () {
    return response()->json(['ok' => true]);
});

Route::get('/start-session', function () {
    session()->put('key', 'value');
    return response()->json(['token' => csrf_token()]);
});



