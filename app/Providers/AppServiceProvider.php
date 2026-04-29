<?php

namespace App\Providers;

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

    public function boot(): void
    {
        \App\Models\ShippingLabelItem::observe(\App\Observers\StockObserver::class);
        \App\Models\OfflineTransactionItem::observe(\App\Observers\OfflineTransactionItemObserver::class);
    }
}
