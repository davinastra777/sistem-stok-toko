<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OfflineTransactionResource\Pages\CreateOfflineTransaction;
use App\Filament\Resources\OfflineTransactionResource\Pages\EditOfflineTransaction;
use App\Filament\Resources\OfflineTransactionResource\Pages\ListOfflineTransactions;
use App\Models\OfflineTransaction;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB; // Tambahkan ini

class OfflineTransactionResource extends Resource
{
    protected static ?string $model = OfflineTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Forms\Components\DatePicker::make('transaction_date')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->default(now()),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Produk')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            // Baris relationship tetap dikomentari karena kita handle manual di Pages
                            ->label('Daftar Produk')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->options(Product::pluck('nama produk', 'id'))
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
                Tables\Columns\TextColumn::make('transaction_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (OfflineTransaction $record) {
                        // Kembalikan stok sebelum data dihapus
                        DB::transaction(function () use ($record) {
                            foreach ($record->items as $item) {
                                $product = Product::find($item->product_id);
                                if ($product) {
                                    // Gunakan nama kolom dengan spasi sesuai model Anda
                                    $product->increment('jumlah stok', $item->qty);
                                }
                            }
                            // Hapus item detail agar bersih
                            $record->items()->delete();
                        });
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (\Illuminate\Database\Eloquent\Collection $records) {
                            // Loop setiap transaksi yang dicentang
                            DB::transaction(function () use ($records) {
                                foreach ($records as $record) {
                                    foreach ($record->items as $item) {
                                        $product = Product::find($item->product_id);
                                        if ($product) {
                                            $product->increment('jumlah stok', $item->qty);
                                        }
                                    }
                                    $record->items()->delete();
                                }
                            });
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOfflineTransactions::route('/'),
            'create' => CreateOfflineTransaction::route('/create'),
            'edit' => EditOfflineTransaction::route('/{record}/edit'),
        ];
    }
}