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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\TextInput::make('id_produk')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nama produk')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('harga_offline')
                            ->label('Harga Offline')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('harga_online')
                            ->label('Harga Online')
                            ->required()
                            ->numeric(),

                        Forms\Components\TextInput::make('jumlah_stok')
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

                Forms\Components\Hidden::make('harga')
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama produk')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_produk')
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
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_offline')
                    ->label('Harga Offline')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('harga_online')
                    ->label('Harga Online')
                    ->numeric()
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
