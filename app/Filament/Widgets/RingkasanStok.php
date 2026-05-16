<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\OfflineTransaction;
use App\Models\ShippingLabel;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanStok extends BaseWidget
{
    protected ?string $heading = 'Ringkasan Gudang';
    protected ?string $description = 'Metrik stok dan aktivitas transaksi terbaru untuk membantu kendalikan persediaan.';
    protected int | string | array $columnSpan = [
        'default' => 12,
        'md' => 6,
        'lg' => 3,
    ];

    protected function getStats(): array
    {
        $totalStok = Product::query()->sum('jumlah_stok') ?? 0;
        $lowStockCount = Product::query()->where('jumlah_stok', '<', 5)->count() ?? 0;
        $transactionCount = (OfflineTransaction::query()->count('*') ?? 0) + (ShippingLabel::query()->count('*') ?? 0);

        return [
            Stat::make('Total Produk', Product::query()->count('*') ?? 0)
                ->description('Jumlah semua item produk terdaftar')
                ->icon('heroicon-o-cube-transparent')
                ->color('primary')
                ->extraAttributes(['class' => 'group']),

            Stat::make('Total Stok', $this->formatNumber($totalStok))
                ->description('Jumlah semua stok saat ini')
                ->icon('heroicon-o-inbox-stack')
                ->color('success')
                ->extraAttributes(['class' => 'group']),

            Stat::make('Transaksi Hari Ini', $this->formatNumber($transactionCount))
                ->description('Total transaksi dan pengiriman')
                ->icon('heroicon-o-check-circle')
                ->color('info')
                ->extraAttributes(['class' => 'group']),

            Stat::make('Stok Menipis', $this->formatNumber($lowStockCount))
                ->description('Produk dengan stok < 5 pcs')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->extraAttributes(['class' => 'group']),
        ];
    }

    protected function formatNumber(int $number): string
    {
        return number_format($number, 0, ',', '.');
    }
}