<?php

namespace App\Filament\Widgets;

use App\Models\StockEntry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
use Filament\Widgets\LineChartWidget;

class StokMasukChart extends LineChartWidget
{
    protected static ?string $heading = 'Stok Masuk 7 Hari Terakhir';
    protected static ?string $description = 'Jumlah produk masuk setiap hari dalam seminggu terakhir.';
    protected static ?string $maxHeight = '320px';
    protected int | string | array $columnSpan = [
        'default' => 12,
        'md' => 6,
    ];

    protected function getData(): array
    {
        if (! Schema::hasTable('stock_entries')) {
            return [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Qty Masuk',
                        'data' => [],
                    ],
                ],
            ];
        }

        $start = now()->subDays(6)->startOfDay();

        $entries = StockEntry::with('items')
            ->whereBetween('entry_date', [$start, now()])
            ->get();

        $labels = [];
        $values = [];

        for ($days = 6; $days >= 0; $days--) {
            $date = now()->subDays($days);
            $labels[] = $date->format('d M');

            $dailyTotal = $entries
                ->filter(fn (StockEntry $entry) => Carbon::parse($entry->entry_date)->isSameDay($date))
                ->sum(fn (StockEntry $entry) => $entry->items->sum('qty'));

            $values[] = $dailyTotal;
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Qty Masuk',
                'data' => $values,
                'fill' => true,
            ]],
        ];
    }
}
