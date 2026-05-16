<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LaporanStokList extends BaseWidget
{
    protected static ?string $heading = 'Daftar Stok Produk';
    protected int | string | array $columnSpan = [
        'default' => 12,
    ];

    protected function getTableQuery(): Builder
    {
        return Product::query();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama produk')
                ->label('Produk')
                ->limit(40)
                ->tooltip(fn ($record): ?string => $record->{'nama produk'}),

            Tables\Columns\TextColumn::make('jumlah_stok')
                ->label('Stok')
                ->sortable(),
        ];
    }
}
