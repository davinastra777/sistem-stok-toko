<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    // Agar otomatis pindah ke tabel produk setelah simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Menambahkan notifikasi sukses yang lebih jelas
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Produk Berhasil Ditambahkan';
    }
}