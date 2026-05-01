<?php

namespace App\Filament\Resources\OfflineTransactionResource\Pages;

use App\Filament\Resources\OfflineTransactionResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditOfflineTransaction extends EditRecord
{
    protected static string $resource = OfflineTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * TAHAP 1: Menampilkan data produk saat tombol Edit ditekan
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ambil items dari relasi dan masukkan ke array 'items' agar muncul di Repeater
        $data['items'] = $this->record->items()->get()->toArray();

        return $data;
    }

    /**
     * TAHAP 2: Menghitung ulang total_amount sebelum data disimpan
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $items = $data['items'] ?? [];
        $total = 0;

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $total += $product->harga * ($item['qty'] ?? 0);
            }
        }

        $data['total_amount'] = $total;

        return $data;
    }

    // Logika Update Manual (Menangani Stok & Sinkronisasi Produk)   
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return DB::transaction(function () use ($record, $data) {
                $itemsData = $data['items'] ?? [];
                unset($data['items']);

                // Simpan dulu info stok asli gudang untuk keperluan notifikasi jika error
                $actualStock = [];
                foreach ($itemsData as $item) {
                    $p = Product::find($item['product_id']);
                    if ($p) {
                        $actualStock[$p->id] = $p->{'jumlah stok'};
                    }
                }

                // Kembalikan stok lama (Reset stok sementara di DB)
                foreach ($record->items as $oldItem) {
                    $product = Product::find($oldItem->product_id);
                    if ($product) {
                        $product->increment('jumlah stok', $oldItem->qty);
                    }
                }

                $record->update($data);
                $record->items()->delete();

                // Validasi dengan menggunakan referensi stok asli untuk pesan error
                foreach ($itemsData as $item) {
                    $product = Product::find($item['product_id']);

                    if (!$product) throw new \Exception("Produk tidak ditemukan.");

                    // Cek stok menggunakan stok di DB yang sudah di reset
                    if ($product->{'jumlah stok'} < $item['qty']) {
                        // Tampilkan stok asli yang ada di halaman produk
                        $displayStock = $actualStock[$product->id] ?? 0;
                        throw new \Exception("Stok {$product->{'nama produk'}} tidak mencukupi. Sisa stok di gudang: {$displayStock}");
                    }

                    $record->items()->create([
                        'product_id' => $item['product_id'],
                        'qty' => $item['qty'],
                    ]);

                    $product->decrement('jumlah stok', $item['qty']);
                }

                return $record;
            });
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal Update Transaksi')
                ->danger()
                ->body($e->getMessage())
                ->persistent()
                ->send();

            $this->halt();
            return $record;
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}