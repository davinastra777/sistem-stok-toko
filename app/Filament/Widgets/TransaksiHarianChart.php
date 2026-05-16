<?php

namespace App\Filament\Widgets;

use App\Models\OfflineTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Filament\Widgets\BarChartWidget;

class TransaksiHarianChart extends BarChartWidget
{
    protected static ?string $heading = 'Transaksi Harian 7 Hari';
    protected static ?string $description = 'Jumlah transaksi offline yang tercatat dalam seminggu terakhir.';
    protected static ?string $maxHeight = '320px';
    protected int | string | array $columnSpan = [
        'default' => 12,
        'md' => 6,
    ];

    protected function getData(): array
    {
        if (! Schema::hasTable('offline_transactions')) {
            return [
                'labels' => [],
                'datasets' => [
                    [
                        'label' => 'Transaksi',
                        'data' => [],
                    ],
                ],
            ];
        }

        $start = now()->subDays(6)->startOfDay();

        $transactions = OfflineTransaction::query()
            ->whereBetween('transaction_date', [$start, now()])
            ->get();

        $labels = [];
        $values = [];

        for ($days = 6; $days >= 0; $days--) {
            $date = now()->subDays($days);
            $labels[] = $date->format('d M');

            $dailyCount = $transactions
                ->filter(fn (OfflineTransaction $transaction) => Carbon::parse($transaction->transaction_date)->isSameDay($date))
                ->count();

            $values[] = $dailyCount;
        }

        return [
            'labels' => $labels,
            'datasets' => [[
                'label' => 'Transaksi Offline',
                'data' => $values,
                'backgroundColor' => 'rgba(55, 125, 255, 0.6)',
            ]],
        ];
    }
}
