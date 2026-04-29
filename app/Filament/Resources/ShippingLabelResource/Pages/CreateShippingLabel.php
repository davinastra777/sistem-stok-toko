<?php

namespace App\Filament\Resources\ShippingLabelResource\Pages;

use App\Filament\Resources\ShippingLabelResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateShippingLabel extends CreateRecord
{
    protected static string $resource = ShippingLabelResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $items = $data['items'] ?? [];

        // Validasi stok sebelum create
        foreach ($items as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                $this->halt('Produk tidak ditemukan.');
            }
            if ($product->stock < $item['qty']) {
                $this->halt('Stok produk ' . $product->{'nama produk'} . ' tidak cukup. Stok tersedia: ' . $product->stock);
            }
        }
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        return DB::transaction(function () use ($data) {
            return static::getModel()::create($data);
        });
    }
}
