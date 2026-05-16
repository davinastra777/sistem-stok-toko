<?php

namespace App\Filament\Resources\StockEntryResource\Pages;

use App\Filament\Resources\StockEntryResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditStockEntry extends EditRecord
{
    protected static string $resource = StockEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['items'] = $this->record->items()->get(['product_id', 'qty'])->toArray();

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            return DB::transaction(function () use ($record, $data) {
                $items = $data['items'] ?? [];
                unset($data['items']);

                foreach ($record->items as $oldItem) {
                    DB::table('products')
                        ->where('id', $oldItem->product_id)
                        ->decrement('jumlah_stok', $oldItem->qty);
                }

                $record->update($data);
                $record->items()->delete();

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

                    $record->items()->create([
                        'product_id' => $product->id,
                        'qty' => $qty,
                    ]);

                    DB::table('products')
                        ->where('id', $product->id)
                        ->increment('jumlah_stok', $qty);
                }

                return $record;
            });
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal memperbarui stok masuk')
                ->body($e->getMessage())
                ->danger()
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
