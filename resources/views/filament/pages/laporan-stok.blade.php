<x-filament-panels::page>
    <div class="space-y-6">

        {{-- 1. HEADER BANNER MODERN --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-650 p-6 shadow-sm dark:from-indigo-900 dark:to-slate-900 border border-transparent dark:border-gray-800">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center rounded-md bg-black/20 px-2.5 py-0.5 text-xs font-semibold text-white uppercase tracking-wider backdrop-blur-md">
                        Laporan Real-Time
                    </span>
                    <h1 class="mt-2 text-2xl font-bold tracking-tight text-white sm:text-3xl">
                        Laporan Arus Stok Gudang
                    </h1>
                    <p class="mt-1 text-sm text-blue-100/90">
                        Pantau pergerakan stok masuk serta sisa kuantitas fisik kerupuk kemplang hari ini.
                    </p>
                </div>
                
                <div class="flex items-center gap-2 self-start md:self-center bg-white/10 backdrop-blur-md rounded-xl p-3 text-white border border-white/10 shadow-inner">
                    <svg class="h-5 w-5 text-blue-200" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
                    </svg>
                    <span class="text-xs font-bold tracking-wide uppercase">Sistem Inventaris</span>
                </div>
            </div>
            
            <div class="absolute -top-10 -right-10 h-36 w-36 rounded-full bg-white/10 blur-xl"></div>
            <div class="absolute -bottom-10 left-1/3 h-28 w-28 rounded-full bg-indigo-500/20 blur-lg"></div>
        </div>

        {{-- 2. PANEL GRAFIK UTAMA --}}
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100 dark:bg-gray-900 dark:border-gray-800/60">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-4 mb-6 dark:border-gray-800 gap-2">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Visualisasi Pergerakan Stok</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Analisis grafik pasokan berkala</p>
                    </div>
                </div>
            </div>

            <div>
                <x-filament-widgets::widgets
                    :columns="['default' => 1]"
                    :data="[]"
                    :widgets="[\App\Filament\Widgets\StokMasukChart::class]"
                />
            </div>
        </div>
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-gray-100 dark:bg-gray-900 dark:border-gray-800/60">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 pb-4 mb-6 dark:border-gray-800 gap-2">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-gray-900 dark:text-white">Lembar Kuantitas Produk</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Detail rincian sisa kapasitas produk riil yang tersedia di rak</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>