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

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Transaksi Online';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\Section::make('Upload Dokumen')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('image_path')
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
            $text = preg_replace('/\r\n|\r/', "\n", $text);
            $lines = array_values(array_map('trim', explode("\n", $text)));
            $inProductSection = false;

            for ($i = 0; $i < count($lines); $i++) {
                $line = $lines[$i];

                if (stripos($line, '#Nama Produk') !== false || stripos($line, 'Nama Produk') !== false) {
                    $inProductSection = true;
                    continue;
                }

                if (! $inProductSection) {
                    continue;
                }

                if (stripos($line, 'Pesan:') !== false) {
                    $inProductSection = false;
                    continue;
                }

                if (! preg_match('/^\d+/', $line)) {
                    continue;
                }

                $line = preg_replace('/^\d+\s*/', '', $line);
                $namaProduk = $line;
                $qty = null;
                $hasLineQty = false;

                if (preg_match('/^(.*?)(?:\t+|\s{2,})(\d+)$/', $line, $match)) {
                    $namaProduk = trim($match[1]);
                    $qty = (int) $match[2];
                    $hasLineQty = true;
                }

                while (isset($lines[$i + 1]) && trim($lines[$i + 1]) !== '' && ! preg_match('/^\d+/', $lines[$i + 1])) {
                    $nextLine = trim($lines[$i + 1]);

                    if (stripos($nextLine, 'Pesan:') !== false) {
                        $inProductSection = false;
                        break;
                    }

                    if (preg_match('/^\d+$/', $nextLine)) {
                        $qty = (int) $nextLine;
                        $i++;
                        break;
                    }

                    if ($hasLineQty) {
                        break;
                    }

                    $namaProduk .= ' ' . $nextLine;
                    $i++;
                }

                if ($qty === null && isset($lines[$i + 1]) && preg_match('/^\d+$/', trim($lines[$i + 1]))) {
                    $qty = (int) trim($lines[++$i]);
                }

                if ($qty === null && ! $hasLineQty && preg_match('/^(.*\D)\s+(\d+)$/', $namaProduk, $match)) {
                    $namaProduk = trim($match[1]);
                    $qty = (int) $match[2];
                }

                if ($qty === null) {
                    continue;
                }

                $namaProduk = ShippingLabel::normalizeProductName($namaProduk);
                $upperName = strtoupper($namaProduk);

                if ($qty < 1) {
                    continue;
                }

                if (
                    str_contains($upperName, 'BERAT') ||
                    str_contains($upperName, 'BATAS KIRIM') ||
                    str_contains($upperName, 'KOTA') ||
                    str_contains($upperName, 'SKU VARIASI') ||
                    str_contains($upperName, 'PESAN:')
                ) {
                    continue;
                }

                $key = mb_strtoupper($namaProduk, 'UTF-8');
                if (isset($items[$key])) {
                    $items[$key]['qty'] += $qty;
                } else {
                    $items[$key] = [
                        'produk' => $namaProduk,
                        'qty' => $qty,
                    ];
                }
            }

            $set('items', array_values($items));
            
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
            \Filament\Tables\Columns\IconColumn::make('image_path')
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