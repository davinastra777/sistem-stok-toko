<?php

namespace App\Filament\Resources;
use Smalot\PdfParser\Parser;
use App\Filament\Resources\ShippingLabelResource\Pages;
use App\Filament\Resources\ShippingLabelResource\RelationManagers;
use App\Models\ShippingLabel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingLabelResource extends Resource
{
    protected static ?string $model = ShippingLabel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Upload Dokumen')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('image')
                            ->label('Pilih File PDF Resi')
                            ->acceptedFileTypes(['application/pdf'])
                            ->required()
                            ->directory('resi')
                            ->live()
                        ->afterStateUpdated(function ($state, \Filament\Forms\Set $set) {
        if (! $state instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) return;

        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($state->getRealPath());
            $text = $pdf->getText();
            $set('raw_text', $text);

        $items = [];

    $sections = explode('Nama Produk', $text);

    foreach ($sections as $section) {

        if (strlen(trim($section)) < 50) continue;

        $endPos = stripos($section, 'Pesan:');
        if ($endPos !== false) {
            $section = substr($section, 0, $endPos);
        }

        $lines = array_values(array_filter(array_map('trim', explode("\n", $section))));

        for ($i = 0; $i < count($lines); $i++) {

            if (preg_match('/^\d+(.*)/', $lines[$i], $match)) {

                $namaProduk = trim($match[1]);

                $namaProduk = preg_replace('/^\d+/', '', $namaProduk);

                while (isset($lines[$i + 1]) && !is_numeric($lines[$i + 1])) {
                    $namaProduk .= ' ' . $lines[$i + 1];
                    $i++;
                }
                if (isset($lines[$i + 1]) && is_numeric($lines[$i + 1])) {

                    $qty = (int) $lines[$i + 1];

                    if (
                        str_contains($namaProduk, 'Berat') ||
                        str_contains($namaProduk, 'Batas Kirim') ||
                        str_contains($namaProduk, 'KOTA')
                    ) {
                        continue;
                    }

                    $items[] = [
                        'produk' => preg_replace('/\s+/', ' ', $namaProduk),
                        'qty' => $qty,
                    ];

                    $i++; 
                }
            }
        }
    }
            $set('items', $items);
            
        } catch (\Exception $e) {
            $set('raw_text', 'Gagal membaca PDF: ' . $e->getMessage());
        }
    })
]),

            \Filament\Forms\Components\Section::make('Rekap Pesanan Otomatis')
                ->description('Data di bawah ini terisi otomatis dari hasil scan PDF.')
                ->schema([
                    \Filament\Forms\Components\Repeater::make('items')
                        ->label(false)
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('produk')->label('Nama Barang')->columnSpan(3),
                            \Filament\Forms\Components\TextInput::make('qty')->label('Qty')->numeric()->columnSpan(1),
                        ])
                        ->columns(4)
                        ->reorderable(false)
                        ->addable(false)
                        ->deletable(true),
                ]),

            \Filament\Forms\Components\Section::make('Log Teks PDF')
                ->collapsed()
                ->schema([
                    \Filament\Forms\Components\Textarea::make('raw_text')
                        ->label('Isi Teks Mentah')
                        ->rows(10)
                        ->readOnly(),
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
