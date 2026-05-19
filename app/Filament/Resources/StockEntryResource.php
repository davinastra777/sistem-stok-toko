<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockEntryResource\Pages;
use App\Models\Product;
use App\Models\StockEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockEntryResource extends Resource
{
    protected static ?string $model = StockEntry::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up';
    protected static ?string $navigationLabel = 'Stok Masuk';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $modelLabel = 'Stok Masuk';
    protected static ?string $pluralModelLabel = 'Stok Masuk';
    
    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Stok Masuk')
                    ->schema([
                        Forms\Components\DatePicker::make('entry_date')
                            ->label('Tanggal Masuk')
                            ->required()
                            ->default(now()),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3)
                            ->nullable(),
                    ]),

                Forms\Components\Section::make('Item Stok')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Daftar Produk')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->options(fn () => Product::query()->where('status', true)->pluck('nama produk', 'id'))
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('qty')
                                    ->label('Qty')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->minItems(1)
                            ->addActionLabel('Tambah Produk'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entry_date')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('total_qty')
                    ->label('Total Qty')
                    ->getStateUsing(fn (StockEntry $record): int => $record->items->sum('qty'))
                    ->sortable(),

                // PERBAIKAN 2: Memaksa pemanggilan nama produk langsung dari database agar tidak hilang/hanya muncul angka
                Tables\Columns\TextColumn::make('products')
                    ->label('Produk')
                    ->getStateUsing(function (StockEntry $record): string {
                        return $record->items
                            ->map(function ($item) {
                                // Ambil data produk langsung berdasarkan product_id bypass bug relasi
                                $product = Product::find($item->product_id);
                                return $product ? $product->{'nama produk'} : '';
                            })
                            ->filter()
                            ->join(', ');
                    })
                    ->wrap(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockEntries::route('/'),
            'create' => Pages\CreateStockEntry::route('/create'),
            'edit' => Pages\EditStockEntry::route('/{record}/edit'),
        ];
    }
}