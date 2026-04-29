<?php

namespace App\Filament\Resources\OfflineTransactionResource\Pages;

use App\Filament\Resources\OfflineTransactionResource;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateOfflineTransaction extends CreateRecord
{
    protected static string $resource = OfflineTransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $items = $data['items'] ?? [];
        $total = 0;

        foreach ($items as $item) {
            $productId = $item['product_id'] ?? null;
            $qty = isset($item['qty']) ? (int) $item['qty'] : 0;

            if (!$productId || $qty < 1) {
                continue;
            }

            $product = Product::find($productId);
            if (!$product) {
                $this->halt('Produk tidak ditemukan.');
            }

            $total += $product->harga * $qty;
        }

        $data['total_amount'] = $total;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return DB::transaction(function () use ($data) {

                $items = $data['items'] ?? [];

                if (empty($items)) {
                    throw new \Exception('Minimal 1 produk harus ditambahkan.');
                }

                unset($data['items']);

                $transaction = static::getModel()::create($data);

                foreach ($items as $item) {
                    $productId = $item['product_id'] ?? null;
                    $qty = isset($item['qty']) ? (int) $item['qty'] : 0;

                    if (!$productId || $qty < 1) {
                        continue;
                    }

                    $product = Product::find($productId);
                    if (!$product) {
                        throw new \Exception('Produk tidak ditemukan.');
                    }

                    // 🔥 FIX FIELD SPASI
                    if ($product->{'jumlah stok'} < $qty) {
                        throw new \Exception(
                            "Stok {$product->{'nama produk'}} tidak cukup. Stok tersedia: {$product->{'jumlah stok'}}"
                        );
                    }

                    // simpan item
                    $transaction->items()->create([
                        'product_id' => $product->id,
                        'qty' => $qty,
                    ]);

                    // 🔥 FIX KURANGI STOK (TIDAK BISA decrement)
                    $product->{'jumlah stok'} -= $qty;
                    $product->save();
                }

                return $transaction;
            });
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal membuat transaksi')
                ->danger()
                ->body($e->getMessage())
                ->send();

            throw $e;
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}