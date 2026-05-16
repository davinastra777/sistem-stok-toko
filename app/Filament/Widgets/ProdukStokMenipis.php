<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class ProdukStokMenipis extends BaseWidget
{
    protected static ?string $heading = 'Produk Hampir Habis';
    protected static ?string $description = 'Daftar produk dengan stok kurang dari 5 pcs yang perlu restok segera.';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = [
        'default' => 12,
        'md' => 6,
    ];

    protected function getTableQuery(): Builder
    {
        return Product::query()
            ->where('jumlah_stok', '<', 5)
            ->orderBy('jumlah_stok', 'asc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama produk')
                ->label('Nama Produk')
                ->limit(30)
                ->tooltip(fn ($record): ?string => $record->nama_produk)
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('jumlah_stok')
                ->label('Stok Saat Ini')
                ->formatStateUsing(fn (int $state): string => (string) $state)
                ->sortable()
                ->badge()
                ->color(fn (int $state): string => $state === 0 ? 'danger' : 'warning'),

            Tables\Columns\TextColumn::make('harga_jual')
                ->label('Harga')
                ->formatStateUsing(fn ($state): string => 'Rp ' . number_format((int) $state, 0, ',', '.'))
                ->alignRight(),
        ];
    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10];
    }
}