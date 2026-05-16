<?php

namespace App\Filament\Widgets;

use App\Models\OfflineTransaction;
use App\Models\ShippingLabel;
use Carbon\Carbon;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\Schema;

class LaporanPenjualanChart extends LineChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan Online & Offline';
    protected static ?string $description = 'Perbandingan jumlah transaksi offline dan produk online terjual dalam 7 hari terakhir.';
    protected static ?string $maxHeight = '360px';
    protected int | string | array $columnSpan = [
        'default' => 12,
        'md' => 6,
    ];

    protected function getData(): array
    {
        if (! Schema::hasTable('offline_transactions') || ! Schema::hasTable('shipping_labels')) {
            return [
                'labels' => [],
                'datasets' => [
                    ['label' => 'Transaksi Offline', 'data' => []],
                    ['label' => 'Produk Online Terjual', 'data' => []],
                ],
            ];
        }

        $start = now()->subDays(6)->startOfDay();

        $transactions = OfflineTransaction::query()
            ->whereBetween('transaction_date', [$start, now()])
            ->get();

        $labels = [];
        $offlineCounts = [];
        $onlineQuantities = [];

        for ($days = 6; $days >= 0; $days--) {
            $date = now()->subDays($days);
            $labels[] = $date->format('d M');

            $dailyOfflineCount = $transactions
                ->filter(fn (OfflineTransaction $transaction) => Carbon::parse($transaction->transaction_date)->isSameDay($date))
                ->count();

            $offlineCounts[] = $dailyOfflineCount;

            $dailyShippingQuantity = ShippingLabel::query()
                ->whereBetween('created_at', [$date->startOfDay(), $date->endOfDay()])
                ->get()
                ->sum(function (ShippingLabel $label) {
                    return collect($label->items)
                        ->reduce(fn ($carry, $item) => $carry + (int) ($item['qty'] ?? 0), 0);
                });

            $onlineQuantities[] = $dailyShippingQuantity;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Transaksi Offline',
                    'data' => $offlineCounts,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.08)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 5,
                    'pointBackgroundColor' => 'rgba(59, 130, 246, 1)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointHoverRadius' => 7,
                ],
                [
                    'label' => 'Produk Online Terjual',
                    'data' => $onlineQuantities,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.08)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 5,
                    'pointBackgroundColor' => 'rgba(16, 185, 129, 1)',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointHoverRadius' => 7,
                ],
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 15,
                        'font' => [
                            'size' => 13,
                            'weight' => '500',
                        ],
                        'color' => '#64748b',
                    ],
                ],
                'tooltip' => [
                    'backgroundColor' => 'rgba(15, 23, 42, 0.8)',
                    'padding' => 12,
                    'cornerRadius' => 8,
                    'titleFont' => [
                        'size' => 14,
                        'weight' => 'bold',
                    ],
                    'bodyFont' => [
                        'size' => 13,
                    ],
                    'displayColors' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'color' => 'rgba(203, 213, 225, 0.2)',
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'color' => '#64748b',
                        'font' => [
                            'size' => 12,
                        ],
                        'padding' => 10,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'color' => '#64748b',
                        'font' => [
                            'size' => 12,
                        ],
                        'padding' => 10,
                    ],
                ],
            ],
        ];
    }
}
