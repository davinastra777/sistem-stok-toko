<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ProdukStokMenipis extends BaseWidget
{
    protected static ? string $heading = 'Produk Stok Menipis';

    protected function getTableQuery(): Builder
    {
        return Product::query()
            ->where('jumlah stok', '<', 5);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama produk')
                ->label('Nama Produk'),

            Tables\Columns\TextColumn::make('jumlah stok')
                ->label('Stok'),

            Tables\Columns\TextColumn::make('harga')
                ->label('Harga')
                ->money('IDR'),
        ];
    }
}