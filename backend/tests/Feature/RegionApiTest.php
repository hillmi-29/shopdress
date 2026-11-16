<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegionApiTest extends TestCase
{
    public function test_can_list_provinces(): void
    {
        $response = $this->getJson('/api/regions/provinces');

        $response->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure(['status', 'data' => [['code', 'name']]]);

        $this->assertGreaterThan(30, count($response->json('data')));
    }

    public function test_can_list_regencies_by_province(): void
    {
        $response = $this->getJson('/api/regions/regencies?province_code=32');

        $response->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure(['status', 'data' => [['code', 'name']]]);

        $this->assertNotEmpty($response->json('data'));
    }

    public function test_can_list_districts_by_regency(): void
    {
        $response = $this->getJson('/api/regions/districts?regency_code=32.73');

        $response->assertOk()
            ->assertJson(['status' => 'success']);

        $this->assertNotEmpty($response->json('data'));
    }

    public function test_can_list_villages_with_postal_codes_by_district(): void
    {
        $response = $this->getJson('/api/regions/villages?district_code=32.73.05');

        $response->assertOk()
            ->assertJson(['status' => 'success'])
            ->assertJsonStructure(['status', 'data' => [['code', 'name', 'postal_code']]]);

        $this->assertNotEmpty($response->json('data'));
    }
}
