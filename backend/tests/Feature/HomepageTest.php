<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_returns_200(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_homepage_displays_hero_section(): void
    {
        $response = $this->get('/');

        $response->assertSee('Temukan dress yang membuatmu merasa');
        $response->assertSee('paling percaya diri');
    }

    public function test_homepage_displays_featured_products_section(): void
    {
        $response = $this->get('/');

        $response->assertSee('Koleksi unggulan');
    }

    public function test_homepage_displays_customer_favorites_section(): void
    {
        $response = $this->get('/');

        $response->assertSee('Kesukaan Pelanggan');
        $response->assertSee('Paling dicari');
    }

    public function test_homepage_displays_value_props_section(): void
    {
        $response = $this->get('/');

        $response->assertSee('Bahan pilihan');
        $response->assertSee('Detail yang rapi');
    }

    public function test_homepage_displays_cta_section(): void
    {
        $response = $this->get('/');

        $response->assertSee('Siap menemukan dress favoritmu');
    }
}
