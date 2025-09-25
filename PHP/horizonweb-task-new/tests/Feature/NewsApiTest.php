<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\Language;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the /news/{country} endpoint correctly fetches news articles.
     *
     * This test:
     *  1. Fakes the external NewsData API response to avoid real HTTP calls.
     *  2. Creates a test country and attaches a language to it.
     *  3. Sends a GET request to the /news/{country} endpoint.
     *  4. Asserts that the response has HTTP 200 status and includes a 'results' key
     *     matching the mocked NewsData response structure.
     *
     * The RefreshDatabase trait ensures a clean database for every test run.
     *
     * @return void
     */

     /** @test */
    public function it_fetches_news_for_country()
    {
        // Mock the external NewsData API response
        Http::fake([
            'newsdata.io/*' => Http::response([
                'results' => [
                    ['title' => 'Fake News 1'],
                    ['title' => 'Fake News 2'],
                ],
                'status' => 'success',
            ], 200)
        ]);

        // Create a test country
        $country = Country::create([
            'name' => 'Testland',
            'code' => 'tl',
        ]);

        // Attach a language so the endpoint has at least one to use
        $lang = Language::create(['code' => 'en']);
        $country->languages()->attach($lang->id);

        //  Send the GET request to the /news/{country} endpoint
        $response = $this->getJson("/news/{$country->code}");

        //  Assert the response status and structure match expectations
        $response->assertStatus(200)
                 ->assertJsonStructure(['results']);
    }
}
