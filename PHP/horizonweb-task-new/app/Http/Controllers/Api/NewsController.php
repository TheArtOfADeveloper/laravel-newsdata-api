<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Services\NewsDataService;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    private NewsDataService $service;

    public function __construct(NewsDataService $service)
    {
        $this->service = $service;
    }

    // /country/{code}/{category}
    public function byCategory(string $code, string $category)
    {
        $country   = Country::where('code', $code)->with('languages')->firstOrFail();
        $languages = $country->languages->pluck('code')->implode(',');

        $data = $this->service->fetch(
    $code,   // ✅ stringa come primo parametro
    [
        'country'  => $code,
        'language' => $languages,
        'category' => $category,
    ]
);

        return response()->json($data, Response::HTTP_OK);
    }

    // /news/{country}/{page?}
    public function paginated(string $country, int $page = 1)
    {
        $c = Country::where('code', $country)
            ->with(['languages','categories'])
            ->firstOrFail();

        $languages  = $c->languages->pluck('code')->implode(',');
        $categories = $c->categories->pluck('name')->implode(',');

        $data = $this->service->fetch(
    $country,    // stringa
    [
        'country'  => $country,
        'language' => $languages,
        'category' => $categories,
    ],
    $page        // intero
);

        return response()->json($data);
    }
}
