<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TransactionHistory extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Riwayat Transaksi';

    protected static string $view = 'filament.pages.transaction-history';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }
}