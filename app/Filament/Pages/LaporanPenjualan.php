<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LaporanPenjualan extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan Penjualan';

    protected static string $view = 'filament.pages.laporan-penjualan';
}
