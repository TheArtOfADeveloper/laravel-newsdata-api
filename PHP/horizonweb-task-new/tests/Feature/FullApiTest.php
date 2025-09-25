<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\Category;
use App\Models\Language;

class FullApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function full_api_flow_works()
    {
        // 1️⃣  Crea un paese con lingua e categoria
        $country = Country::create([
            'name' => 'Testland',
            'code' => 'tl',
        ]);

        $language = Language::create(['code' => 'en']);
        $country->languages()->attach($language->id);

        $category = Category::create(['name' => 'sports']);
        $country->categories()->attach($category->id);

        // 2️⃣  GET /countries  (lista completa)
        $this->getJson('/countries')
            ->assertStatus(200)
            ->assertJsonStructure([
                'countries' => [
                    '*' => ['name','code','languages','categories']
                ]
            ]);

        // 3️⃣  GET /country/{code}  (singolo paese)
        $this->getJson("/country/{$country->code}")
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Testland',
                'code' => 'tl'
            ]);

        // 4️⃣  DELETE /country/{code}/category/{category}
        $this->deleteJson("/country/{$country->code}/category/{$category->name}")
            ->assertStatus(200)
            ->assertJsonFragment([
                'status' => 'success'
            ]);

        // assicura che il collegamento nella tabella pivot sia stato rimosso
        $this->assertDatabaseMissing('country_category', [
            'country_id'  => $country->id,
            'category_id' => $category->id,
        ]);

        // 5️⃣  GET /news/{country}
        Http::fake([
            'newsdata.io/*' => Http::response([
                'results' => [
                    ['title' => 'Fake News 1'],
                    ['title' => 'Fake News 2'],
                ],
                'status' => 'success',
            ], 200)
        ]);

        $this->getJson("/news/{$country->code}")
            ->assertStatus(200)
            ->assertJsonStructure(['results']);
    }
}
