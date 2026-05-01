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
            if ($product) {
                // Menggunakan nama kolom sesuai database Anda
                $total += $product->harga * $qty;
            }
        }

        $data['total_amount'] = $total;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return DB::transaction(function () use ($data) {
                $items = $data['items'] ?? [];

                // Cek jika repeater kosong
                if (empty($items)) {
                    Notification::make()
                        ->title('Error')
                        ->body('Minimal 1 produk harus ditambahkan.')
                        ->danger()
                        ->send();

                    $this->halt();
                }

                // Pisahkan data items agar tidak ikut masuk ke create OfflineTransaction
                unset($data['items']);

                // 1. Buat Header Transaksi
                $transaction = static::getModel()::create($data);

                // 2. Proses setiap Produk
                foreach ($items as $item) {
                    $productId = $item['product_id'] ?? null;
                    $qty = isset($item['qty']) ? (int) $item['qty'] : 0;

                    if (!$productId || $qty < 1) {
                        continue;
                    }

                    $product = Product::find($productId);

                    if (!$product) {
                        throw new \Exception("Produk dengan ID {$productId} tidak ditemukan.");
                    }

                    // Cek Stok (Gunakan nama kolom dengan spasi sesuai model)
                    if ($product->{'jumlah stok'} < $qty) {
                        throw new \Exception("Stok {$product->{'nama produk'}} tidak mencukupi (Sisa: {$product->{'jumlah stok'}}).");
                    }

                    // Simpan Item Transaksi (Relasi hasMany)
                    $transaction->items()->create([
                        'product_id' => $product->id,
                        'qty' => $qty,
                    ]);

                    // Potong Stok
                    $product->decrement('jumlah stok', $qty);
                }

                return $transaction;
            });
        } catch (\Throwable $e) {
            // Jika ada error (termasuk stok kurang), batalkan semua transaksi
            Notification::make()
                ->title('Gagal membuat transaksi')
                ->danger()
                ->body($e->getMessage())
                ->send();

            $this->halt();
            return static::getModel()::make();
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    // test
}