<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LaporanStok extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan Stok';

    protected static string $view = 'filament.pages.laporan-stok';
}
