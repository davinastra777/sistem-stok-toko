<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \App\Models\ShippingLabelItem::observe(\App\Observers\StockObserver::class);
        \App\Models\OfflineTransactionItem::observe(\App\Observers\OfflineTransactionItemObserver::class);
        
        // Configure Filament Colors with Professional Palette
        FilamentColor::register([
            'primary' => Color::Sky,
            'success' => Color::Green,
            'warning' => Color::Amber,
            'danger' => Color::Red,
            'gray' => Color::Slate,
        ]);
    }
}
