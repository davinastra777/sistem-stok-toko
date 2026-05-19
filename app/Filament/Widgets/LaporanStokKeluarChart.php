<?php

namespace App\Filament\Widgets;

use App\Models\OfflineTransactionItem;
use App\Models\ShippingLabel;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\Schema;

class LaporanStokKeluarChart extends LineChartWidget
{
    // protected static ?string $heading = 'Stok Keluar 7 Hari Terakhir';
    // protected static ?string $description = 'Jumlah produk keluar setiap hari dari transaksi offline dan online.';
    protected static ?string $maxHeight = '320px';
    protected int | string | array $columnSpan = [
        'default' => 12,
        'md' => 6,
    ];

    protected function getData(): array
    {
        if (! Schema::hasTable('offline_transaction_items') || ! Schema::hasTable('shipping_labels')) {
            return [
                'labels' => [],
                'datasets' => [
                    ['label' => 'Qty Keluar', 'data' => []],
                ],
            ];
        }

        $start = now()->subDays(6)->startOfDay();

        $offlineItems = OfflineTransactionItem::query()
            ->with('transaction')
            ->whereHas('transaction', fn ($query) => $query->whereBetween('transaction_date', [$start, now()]))
            ->get();

        $labels = [];
        $values = [];

        for ($days = 6; $days >= 0; $days--) {
            $date = now()->subDays($days);
            $labels[] = $date->format('d M');

            $dailyOfflineQty = $offlineItems
                ->filter(fn (OfflineTransactionItem $item) => Carbon::parse($item->transaction->transaction_date)->isSameDay($date))
                ->sum('qty');

            $dailyOnlineQty = ShippingLabel::query()
                ->whereBetween('created_at', [$date->copy()->startOfDay(), $date->copy()->endOfDay()])
                ->get()
                ->sum(function (ShippingLabel $label) {
                    return collect($label->items)->sum(fn ($item) => (int) ($item['qty'] ?? 0));
                });

            $values[] = $dailyOfflineQty + $dailyOnlineQty;
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Qty Keluar',
                'data' => $values,
                'fill' => true,
            ]],
        ];
    }
}