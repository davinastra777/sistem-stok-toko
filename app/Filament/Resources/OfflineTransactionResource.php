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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;

class OfflineTransactionResource extends Resource
{
    protected static ?string $model = OfflineTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Transaksi Offline';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Forms\Components\DatePicker::make('transaction_date')
                            ->label('Tanggal Transaksi')
                            ->required()
                            ->default(now())
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan')
                            ->required()
                            ->helperText("Wajib Isi Nama Pembeli")
                            ->rows(3),  
                    ]),

                Forms\Components\Section::make('Produk')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Daftar Produk')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->options(Product::where('status', true)->pluck('nama produk', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->live()
                                    ->afterStateUpdated(fn (Set $set) => $set('qty', 1)),
                                    
                                Forms\Components\TextInput::make('qty')
                                    ->label('Qty')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->maxValue(function (Get $get) {                                        
                                        $productId = $get('product_id');
                                        if ($productId) {
                                            return Product::find($productId)?->jumlah_stok ?? 0;
                                        }
                                        return null;
                                    })                                    
                                    ->helperText(function (Get $get) {
                                        $productId = $get('product_id');
                                        if ($productId) {
                                            $stok = Product::find($productId)?->jumlah_stok ?? 0;
                                            
                                            // Beri peringatan warna jika stok habis
                                            if ($stok <= 0) {
                                                return 'Stok Habis!';
                                            }
                                            
                                            return "Sisa stok tersedia: {$stok}";
                                        }
                                        return 'Pilih produk untuk melihat sisa stok';
                                    }),
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
                    ->money('Rp.')
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
                        DB::transaction(function () use ($record) {
                            foreach ($record->items as $item) {
                                DB::table('products')
                                    ->where('id', $item->product_id)
                                    ->increment('jumlah_stok', $item->qty);
                            }
                            $record->items()->delete();
                        });
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (\Illuminate\Database\Eloquent\Collection $records) {
                            DB::transaction(function () use ($records) {
                                foreach ($records as $record) {
                                    foreach ($record->items as $item) {
                                        DB::table('products')
                                            ->where('id', $item->product_id)
                                            ->increment('jumlah_stok', $item->qty);
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