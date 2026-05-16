<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class TopProduk extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected static ?string $heading = 'Produk dengan Stok Terbesar';

    protected function getTableQuery(): Builder
    {
        return Product::query()
            ->orderByDesc('jumlah_stok')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama produk')
                ->label('Nama Produk')
                ->wrap(),

            Tables\Columns\TextColumn::make('jumlah_stok')
                ->label('Stok')
                ->sortable(),

            Tables\Columns\TextColumn::make('harga_offline')
                ->label('Harga Offline')
                ->money('IDR'),
        ];
    }
}
