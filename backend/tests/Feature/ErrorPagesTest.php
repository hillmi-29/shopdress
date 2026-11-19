<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_404_page_loads_with_custom_design(): void
    {
        $response = $this->get('/halaman-yang-tidak-ada-12345');

        $response->assertStatus(404)
            ->assertSee('HALAMAN TIDAK DITEMUKAN')
            ->assertSee('BERANDA')
            ->assertSee('KATALOG PRODUK')
            ->assertSee('PROMO SPESIAL')
            ->assertSee('404');
    }

    public function test_403_page_loads_with_custom_design(): void
    {
        // Test the blade view renders correctly by viewing it directly
        $view = view('errors.403')->render();
        $this->assertStringContainsString('AKSES DITOLAK', $view);
        $this->assertStringContainsString('403', $view);
        $this->assertStringContainsString('MASUK', $view);
        $this->assertStringContainsString('DAFTAR', $view);
    }

    public function test_419_page_loads_with_custom_design(): void
    {
        $view = view('errors.419')->render();
        $this->assertStringContainsString('SESI BERAKHIR', $view);
        $this->assertStringContainsString('419', $view);
        $this->assertStringContainsString('MUAT ULANG HALAMAN', $view);
        $this->assertStringContainsString('MASUK KEMBALI', $view);
    }

    public function test_429_page_loads_with_custom_design(): void
    {
        $view = view('errors.429')->render();
        $this->assertStringContainsString('TERLALU BANYAK PERMINTAAN', $view);
        $this->assertStringContainsString('429', $view);
        $this->assertStringContainsString('TUNGGU & COBA LAGI', $view);
        $this->assertStringContainsString('BANTUAN', $view);
    }

    public function test_500_page_loads_with_custom_design(): void
    {
        $view = view('errors.500')->render();
        $this->assertStringContainsString('KESALAHAN SERVER', $view);
        $this->assertStringContainsString('500', $view);
        $this->assertStringContainsString('MUAT ULANG', $view);
        $this->assertStringContainsString('HUBUNGI KAMI', $view);
    }

    public function test_503_page_loads_with_custom_design(): void
    {
        $view = view('errors.503')->render();
        $this->assertStringContainsString('SEDANG DIPERBAIKI', $view);
        $this->assertStringContainsString('503', $view);
        $this->assertStringContainsString('MUAT ULANG NANTI', $view);
        $this->assertStringContainsString('HUBUNGI KAMI', $view);
    }

    public function test_all_error_pages_extend_frontend_layout(): void
    {
        $codes = ['403', '404', '419', '429', '500', '503'];

        foreach ($codes as $code) {
            $view = view("errors.{$code}")->render();
            $this->assertStringContainsString('ShopDress', $view); // layout renders brand
            $this->assertStringContainsString('footer', $view); // layout has footer
        }
    }

    public function test_error_pages_have_brand_colors(): void
    {
        $codes = ['403', '404', '419', '429', '500', '503'];

        foreach ($codes as $code) {
            $view = view("errors.{$code}")->render();
            $this->assertStringContainsString('var(--brand)', $view);
            $this->assertStringContainsString('var(--surface)', $view);
            $this->assertStringContainsString('var(--text)', $view);
        }
    }

    public function test_error_pages_have_search_form(): void
    {
        $codes = ['403', '404', '419', '429', '500', '503'];

        foreach ($codes as $code) {
            $view = view("errors.{$code}")->render();
            $this->assertStringContainsString('action="', $view); // form has action
            $this->assertStringContainsString('name="search"', $view);
        }
    }

    public function test_error_pages_respect_reduced_motion(): void
    {
        $codes = ['403', '404', '419', '429', '500', '503'];

        foreach ($codes as $code) {
            $view = view("errors.{$code}")->render();
            $this->assertStringContainsString('prefers-reduced-motion: reduce', $view);
            $this->assertStringContainsString('animation: none !important', $view);
        }
    }
}
