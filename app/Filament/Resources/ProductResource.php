<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Produk';
    protected static ?string $navigationGroup = 'Master Data';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('id_produk')
                            ->label('ID Produk')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nama produk') 
                            ->label('Nama Produk')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('harga_offline')
                            ->label('Harga Offline')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'), // Bonus agar lebih rapi

                        Forms\Components\TextInput::make('harga_online')
                            ->label('Harga Online')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('jumlah_stok')
                            ->label('Jumlah Stok')
                            ->required()
                            ->numeric()
                            ->default(0),

                        Forms\Components\Toggle::make('status')
                            ->label('Status Produk')
                            ->onIcon('heroicon-o-check-circle')
                            ->offIcon('heroicon-o-x-circle')
                            ->default(true)
                            ->helperText('Nonaktifkan jika produk tidak diproduksi lagi.'),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama produk')
                    ->label('Nama Produk')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('id_produk')
                    ->label('ID Produk')
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Aktif' : 'Tidak Aktif')
                    ->colors([
                        'success' => true,
                        'danger' => false,
                    ])
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('jumlah_stok')
                    ->label('Stok')
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('harga_offline')
                    ->label('Harga Offline')
                    ->money('IDR')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('harga_online')
                    ->label('Harga Online')
                    ->money('IDR') 
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleStatus')
                    ->label(fn (Product $record): string => $record->status ? 'Nonaktifkan' : 'Aktifkan')
                    ->action(fn (Product $record) => $record->update(['status' => ! $record->status]))
                    ->color(fn (Product $record): string => $record->status ? 'danger' : 'success'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    
}
