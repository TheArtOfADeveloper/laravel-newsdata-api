<?php

namespace Tests\Feature;

use Tests\TestCase;

class CountryApiTest extends TestCase
{
    /** @test */
    public function it_returns_all_countries()
    {
        $response = $this->getJson('/countries');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'countries' => [
                    '*' => ['name', 'code', 'languages', 'categories']
                ]
            ]);
    }
}
