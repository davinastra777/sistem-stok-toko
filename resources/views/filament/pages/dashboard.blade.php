<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Section: Omset Penjualan (Aksen Biru/Indigo) -->
    <section class="group rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60 overflow-hidden transition-all duration-300 hover:shadow-md hover:ring-blue-200">
        <!-- Garis Gradien Atas -->
        <div class="h-2 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600"></div>
        
        <div class="p-6 sm:p-8">
            <div class="flex items-start justify-between border-b border-slate-100 pb-5">
                <div class="space-y-2 max-w-3xl">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 group-hover:text-blue-600 transition-colors duration-200">Omset Penjualan</h1>
                    <p class="text-sm text-slate-500">Ringkasan omset offline, online, dan total penjualan.</p>
                </div>
                <!-- Icon Ilustrasi Penjualan -->
                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-100 transition-colors duration-200 hidden sm:block">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="mt-6 rounded-xl bg-slate-50/60 p-4 ring-1 ring-slate-100">
                @livewire(\App\Filament\Widgets\LaporanPenjualanOmzet::class)
            </div>
        </div>
    </section>

    <!-- Section: Ringkasan Gudang (Aksen Hijau/Emerald) -->
    <section class="group rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/60 overflow-hidden transition-all duration-300 hover:shadow-md hover:ring-emerald-200">
        <!-- Garis Gradien Atas -->
        <div class="h-2 bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-600"></div>
        
        <div class="p-6 sm:p-8">
            <div class="flex items-start justify-between border-b border-slate-100 pb-5">
                <div class="space-y-2 max-w-3xl">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 group-hover:text-emerald-600 transition-colors duration-200">Ringkasan Gudang</h1>
                    <p class="text-sm text-slate-500">Metrik stok dan aktivitas transaksi terbaru untuk membantu kendalikan persediaan.</p>
                </div>
                <!-- Icon Ilustrasi Gudang -->
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-100 transition-colors duration-200 hidden sm:block">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
            </div>

            <div class="mt-6 rounded-xl bg-slate-50/60 p-4 ring-1 ring-slate-100">
                @livewire(\App\Filament\Widgets\RingkasanStok::class)
            </div>
        </div>
    </section>

</div>