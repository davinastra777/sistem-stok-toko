<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\OfflineTransaction;
use App\Models\ShippingLabel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanStok extends BaseWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Total Produk', Product::count()),

            Stat::make('Total Stok', Product::all()->sum(function ($p) {
                return $p->{'jumlah stok'};
            })),

            Stat::make('Transaksi Hari Ini',
                OfflineTransaction::count() + ShippingLabel::count()
            ),

            Stat::make('Produk Hampir Habis',
                Product::all()->filter(function ($p) {
                    return $p->{'jumlah stok'} < 5;
                })->count()
            ),

        ];
    }
}