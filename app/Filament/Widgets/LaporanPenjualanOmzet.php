<?php

namespace App\Filament\Widgets;

use App\Models\OfflineTransaction;
use App\Models\Product;
use App\Models\ShippingLabel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Schema;

class LaporanPenjualanOmzet extends BaseWidget
{
    protected ?string $heading = 'Omset Penjualan';
    protected ?string $description = 'Ringkasan omset offline, online, dan total penjualan.';
    protected int | string | array $columnSpan = [
        'default' => 12,
        'md' => 6,
        'lg' => 3,
    ];

    protected function getStats(): array
    {
        $offlineOmset = 0;
        $onlineOmset = 0;
        $onlineQty = 0;

        if (Schema::hasTable('offline_transactions')) {
            $offlineOmset = OfflineTransaction::query()->sum('total_amount');
        }

        if (Schema::hasTable('shipping_labels') && Schema::hasTable('products')) {
            $onlineOmset = ShippingLabel::query()->get()->sum(function (ShippingLabel $label) {
                return collect($label->items)->sum(function ($item) {
                    $qty = (int) ($item['qty'] ?? 0);
                    $productName = ShippingLabel::normalizeProductName($item['produk'] ?? '');
                    $product = Product::query()
                        ->whereRaw('TRIM(`nama produk`) = ?', [$productName])
                        ->first();

                    if (! $product) {
                        return 0;
                    }

                    return $qty * ($product->harga_online ?? 0);
                });
            });

            $onlineQty = ShippingLabel::query()->get()->sum(function (ShippingLabel $label) {
                return collect($label->items)->sum(fn ($item) => (int) ($item['qty'] ?? 0));
            });
        }

        return [
            Stat::make('Omset Offline', $this->formatRupiah($offlineOmset))
                ->description('Total pendapatan dari transaksi offline')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->extraAttributes(['class' => 'group cursor-pointer']),

            Stat::make('Omset Online', $this->formatRupiah($onlineOmset))
                ->description('Perkiraan pendapatan transaksi online')
                ->icon('heroicon-o-shopping-cart')
                ->color('primary')
                ->extraAttributes(['class' => 'group cursor-pointer']),

            Stat::make('Total Omset', $this->formatRupiah($offlineOmset + $onlineOmset))
                ->description('Jumlah omset keseluruhan')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('warning')
                ->extraAttributes(['class' => 'group cursor-pointer']),

            Stat::make('Produk Terjual', $this->formatNumber($onlineQty))
                ->description('Jumlah produk terjual online')
                ->icon('heroicon-o-shopping-bag')
                ->color('secondary')
                ->extraAttributes(['class' => 'group cursor-pointer']),
        ];
    }

    protected function formatRupiah(int | float $amount): string
    {
        $amount = (int) $amount;
        
        if ($amount >= 1000000) {
            return 'Rp ' . number_format($amount / 1000000, 0, ',', '.') . 'J';
        } elseif ($amount >= 1000) {
            return 'Rp ' . number_format($amount / 1000, 0, ',', '.') . 'K';
        }
        
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }

    protected function formatNumber(int $number): string
    {
        return number_format($number, 0, ',', '.');
    }
}
