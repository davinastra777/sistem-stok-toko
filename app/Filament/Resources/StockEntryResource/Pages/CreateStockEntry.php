<?php

namespace App\Filament\Resources\StockEntryResource\Pages;

use App\Filament\Resources\StockEntryResource;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateStockEntry extends CreateRecord
{
    protected static string $resource = StockEntryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return DB::transaction(function () use ($data) {
                $items = $data['items'] ?? [];

                if (empty($items)) {
                    Notification::make()
                        ->title('Gagal menyimpan stok masuk')
                        ->body('Minimal satu produk harus ditambahkan.')
                        ->danger()
                        ->send();

                    $this->halt();
                }

                unset($data['items']);

                $stockEntry = static::getModel()::create($data);

                foreach ($items as $item) {
                    $productId = $item['product_id'] ?? null;
                    $qty = isset($item['qty']) ? (int) $item['qty'] : 0;

                    if (! $productId || $qty < 1) {
                        continue;
                    }

                    $product = Product::query()->find($productId);
                    if (! $product) {
                        throw new \Exception("Produk dengan ID {$productId} tidak ditemukan.");
                    }

                    $stockEntry->items()->create([
                        'product_id' => $product->id,
                        'qty' => $qty,
                    ]);

                    DB::table('products')
                        ->where('id', $product->id)
                        ->increment('jumlah_stok', $qty);
                }

                return $stockEntry;
            });
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal menyimpan stok masuk')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
            return static::getModel()::make();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
