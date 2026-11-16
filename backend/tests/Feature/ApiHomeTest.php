<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiHomeTest extends TestCase
{
    use RefreshDatabase;
    public function test_api_home_returns_json(): void
    {
        $response = $this->getJson('/api/home');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'categories',
                    'featured_products',
                    'latest_products',
                ],
            ]);
    }

    public function test_api_home_status_is_success(): void
    {
        $response = $this->getJson('/api/home');

        $response->assertJson(['status' => 'success']);
    }
}
