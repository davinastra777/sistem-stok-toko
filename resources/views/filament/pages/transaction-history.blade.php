<x-filament-panels::page>
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="filament-card">
                <div class="filament-card-header">
                    <h3 class="text-lg font-semibold">Transaksi Offline</h3>
                </div>
                <div class="filament-card-body">
                    <p>Jumlah transaksi: {{ \App\Models\OfflineTransaction::count() }}</p>
                    <a href="{{ \App\Filament\Resources\OfflineTransactionResource::getUrl('index') }}" class="filament-link">Lihat Semua</a>
                </div>
            </div>

            <div class="filament-card">
                <div class="filament-card-header">
                    <h3 class="text-lg font-semibold">Transaksi Online</h3>
                </div>
                <div class="filament-card-body">
                    <p>Jumlah transaksi: {{ \App\Models\ShippingLabel::count() }}</p>
                    <a href="{{ \App\Filament\Resources\ShippingLabelResource::getUrl('index') }}" class="filament-link">Lihat Semua</a>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>