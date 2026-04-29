<?php

namespace App\Filament\Resources;
use Smalot\PdfParser\Parser;
use App\Filament\Resources\ShippingLabelResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShippingLabelResource extends Resource
{
    protected static ?string $model = ShippingLabel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Shipping Label')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->label('File PDF Resi')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('resi'),
                        Forms\Components\Textarea::make('raw_text')
                            ->label('Catatan')
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Produk')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->options(Product::all()->pluck('nama produk', 'id'))
                                    ->required()
                                    ->searchable(),
                                Forms\Components\TextInput::make('qty')
                                    ->label('Qty')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1),
                            ])
                            ->columns(2)
                            ->createItemButtonLabel('Tambah Produk')
                            ->minItems(1),
                    ]),
            ]);
    }
   public static function table(Table $table): Table
{
    return $table
        ->columns([
            \Filament\Tables\Columns\IconColumn::make('image')
                ->label('File PDF')
                ->icon('heroicon-o-document-text')
                ->color('primary'),
                
            \Filament\Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Upload')
                ->dateTime()
                ->sortable(),
                
            \Filament\Tables\Columns\TextColumn::make('raw_text')
                ->label('Preview Isi')
                ->limit(50), 
        ])
        ->filters([
        ])
        ->actions([
            \Filament\Tables\Actions\EditAction::make(),
            \Filament\Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            \Filament\Tables\Actions\BulkActionGroup::make([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}


    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingLabels::route('/'),
            'create' => Pages\CreateShippingLabel::route('/create'),
            'edit' => Pages\EditShippingLabel::route('/{record}/edit'),
        ];
    }
}
