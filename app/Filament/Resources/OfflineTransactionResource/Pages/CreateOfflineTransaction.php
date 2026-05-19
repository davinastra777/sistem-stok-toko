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

    protected function handleRecordCreation(array $data): Model
    {
        try {
            return DB::transaction(function () use ($data) {
                $items = $data['items'] ?? [];

                if (empty($items)) {
                    throw new \Exception("Minimal 1 produk harus ditambahkan.");
                }

                // Asingkan data items agar Header transaksi bisa terbuat
                unset($data['items']);

                // 1. Buat Header Transaksi
                $transaction = static::getModel()::create($data);

                $totalAmount = 0;

                // 2. Loop produk, hitung total harga, potong stok
                foreach ($items as $item) {
                    $productId = $item['product_id'] ?? null;
                    $qty = isset($item['qty']) ? (int) $item['qty'] : 0;

                    if (!$productId || $qty < 1) continue;

                    $product = Product::find($productId);

                    if (!$product) throw new \Exception("Produk tidak ditemukan.");

                    // Validasi Stok
                    if ($product->jumlah_stok < $qty) {
                        $namaProduk = $product->{'nama produk'} ?? 'Produk';
                        throw new \Exception("Stok {$namaProduk} tidak mencukupi (Sisa: {$product->jumlah_stok}).");
                    }

                    // Ambil harga_offline, kalau 0 ambil harga biasa
                    $harga = $product->harga_offline > 0 ? $product->harga_offline : ($product->harga ?? 0);
                    $totalAmount += ($harga * $qty);

                    // Simpan Detail Transaksi
                    $transaction->items()->create([
                        'product_id' => $product->id,
                        'qty' => $qty,
                    ]);

                    // Potong Stok
                    DB::table('products')->where('id', $product->id)->decrement('jumlah_stok', $qty);
                }

                // 3. Simpan Total Harga ke Database (Aman dari mass assignment)
                $transaction->total_amount = $totalAmount;
                $transaction->save();

                return $transaction;
            });
        } catch (\Throwable $e) {
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
}