<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Category;

class CountryCategoryDeleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_category_from_country()
    {
        // crea paese e categoria a mano
        $country = Country::create([
            'name' => 'Testland',
            'code' => 'tl',
        ]);

        $category = Category::create(['name' => 'sports']);
        $country->categories()->attach($category->id);

        // chiamata DELETE
        $response = $this->deleteJson("/country/{$country->code}/category/{$category->name}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'success']);

        $this->assertDatabaseMissing('country_category', [
            'country_id'  => $country->id,
            'category_id' => $category->id,
        ]);
    }
}
