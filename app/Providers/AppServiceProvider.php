<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView; //
use Filament\View\ktośRenderHook; //
use Illuminate\Support\Facades\Blade; //
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
        // ...
        FilamentView::registerRenderHook(
            'panels::global-search.after', // أو panels::topbar.end
            fn (): string => Blade::render('@livewire(\'language-switcher\')'),
        );
    }
}
