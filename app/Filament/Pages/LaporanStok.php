<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\LaporanStokKeluarChart;
use App\Filament\Widgets\LaporanStokList;

class LaporanStok extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Stok';
    protected static string $view = 'filament.pages.laporan-stok';
    protected function getHeaderWidgets(): array
    {
        return [
            LaporanStokKeluarChart::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            LaporanStokList::class,
        ];
    }
}