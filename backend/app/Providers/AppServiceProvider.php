<?php

namespace App\Providers;

use App\View\Composers\FrontendLayoutComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Komponen anonim khusus tampilan pelanggan.
        Blade::anonymousComponentPath(resource_path('views/frontend/components'));

        // Data navbar pelanggan dipisahkan dari file layout.
        View::composer('frontend.layouts.app', FrontendLayoutComposer::class);
    }
}
