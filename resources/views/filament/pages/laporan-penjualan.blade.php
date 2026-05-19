<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 font-sans antialiased">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard Analisis Penjualan</h1>
            <p class="mt-1 text-sm text-slate-500">Pantau metrik keuangan, performa omset, dan pergerakan stok gudang secara real-time.</p>
        </div>
    </div>

    <div class="grid gap-8 grid-cols-1 lg:grid-cols-12">
        
        <section class="lg:col-span-12 bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6 transition-all duration-300 hover:shadow-md">
            <div class="mb-6 flex items-start justify-between border-b border-slate-50 pb-4">
                <div class="flex items-center space-x-3.5">
                    <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl shadow-sm ring-1 ring-blue-100/50">
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Omset Penjualan</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Ringkasan omset offline, online, dan total penjualan.</p>
                    </div>
                </div>
            </div>

            <div class="w-full pt-2">
                @livewire(\App\Filament\Widgets\LaporanPenjualanOmzet::class)
            </div>
        </section>

        <section class="lg:col-span-12 bg-white rounded-2xl border border-slate-200/70 shadow-sm p-6 transition-all duration-300 hover:shadow-md">
            <div class="mb-6 flex items-start justify-between border-b border-slate-50 pb-4">
                <div class="flex items-center space-x-3.5">
                    <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl shadow-sm ring-1 ring-indigo-100/50">
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800 tracking-tight">Grafik Penjualan</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Visualisasi tren dan fluktuasi penjualan produk Anda.</p>
                    </div>
                </div>
            </div>

            <div class="w-full pt-2">
                @livewire(\App\Filament\Widgets\LaporanPenjualanChart::class)
            </div>
        </section>

    </div>
</div>