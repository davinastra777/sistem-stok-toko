<?php

namespace App\Filament\Resources\OfflineTransactionResource\Pages;

use App\Filament\Resources\OfflineTransactionResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditOfflineTransaction extends EditRecord
{
    protected static string $resource = OfflineTransactionResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $items = $data['items'] ?? [];

        // Set harga dari Product untuk setiap item
        foreach ($items as &$item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                $this->halt('Produk tidak ditemukan.');
            }
            $item['price'] = $product->harga; // Override harga dengan harga dari Product
        }

        // Hitung total amount
        $total = 0;
        foreach ($items as $item) {
            $total += $item['qty'] * $item['price'];
        }
        $data['total_amount'] = $total;

        // Validasi stok - perlu hitung perubahan stok
        // Untuk edit, kita perlu bandingkan qty lama vs baru
        $record = $this->record;
        $existingItems = $record->items->keyBy('product_id');

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            $existingQty = $existingItems->get($item['product_id'])->qty ?? 0;
            $qtyDifference = $item['qty'] - $existingQty;

            if ($product->stock < $qtyDifference) {
                $this->halt('Stok produk ' . $product->{'nama produk'} . ' tidak cukup. Stok tersedia: ' . $product->stock);
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
