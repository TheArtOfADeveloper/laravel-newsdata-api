<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\Language;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_news_for_country()
    {
        // finta risposta dell'API esterna
        Http::fake([
            'newsdata.io/*' => Http::response([
                'results' => [
                    ['title' => 'Fake News 1'],
                    ['title' => 'Fake News 2'],
                ],
                'status' => 'success',
            ], 200)
        ]);

        // crea un paese di test senza factory
        $country = Country::create([
            'name' => 'Testland',
            'code' => 'tl',
        ]);

        // se la rotta richiede almeno una lingua, la aggiungiamo
        $lang = Language::create(['code' => 'en']);
        $country->languages()->attach($lang->id);

        // chiamata API
        $response = $this->getJson("/news/{$country->code}");

        // asserzioni
        $response->assertStatus(200)
                 ->assertJsonStructure(['results']);
    }
}
