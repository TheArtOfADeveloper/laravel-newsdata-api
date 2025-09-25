<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Country;
use App\Models\Language;

class CountryShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_single_country()
    {
        // crea paese e lingua a mano
        $country = Country::create([
            'name' => 'Testland',
            'code' => 'tl',
        ]);

        $lang = Language::create(['code' => 'en']);
        $country->languages()->attach($lang->id);

        $response = $this->getJson("/country/{$country->code}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['code' => 'tl']);
    }
}
