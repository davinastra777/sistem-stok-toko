<div class="space-y-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <section class="overflow-hidden rounded-[2rem] bg-slate-950 text-slate-50 shadow-2xl ring-1 ring-slate-900/15">
        <div class="bg-gradient-to-r from-sky-600 via-indigo-600 to-violet-600 px-6 py-6 sm:px-8 sm:py-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-200/80">Dashboard</p>
                    <h1 class="text-3xl font-bold tracking-tight text-white">Omset Penjualan</h1>
                    <p class="max-w-2xl text-sm text-slate-200/80">Ringkasan omset offline, online, dan total penjualan untuk pemantauan cepat dan akurat.</p>
                </div>
                <div class="inline-flex items-center gap-3 rounded-full border border-white/10 bg-white/10 px-4 py-3 text-sm text-white shadow-sm backdrop-blur">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/><path d="M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <div>
                        <div class="text-xs uppercase tracking-[0.25em] text-slate-200/80">Status</div>
                        <div class="text-sm font-semibold text-white">Dashboard Live</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            @livewire(\App\Filament\Widgets\LaporanPenjualanOmzet::class)
        </div>
    </section>

    <section class="overflow-hidden rounded-[2rem] bg-white shadow-2xl ring-1 ring-slate-200/80">
        <div class="border-b border-slate-200/80 px-6 py-6 sm:px-8 sm:py-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Ringkasan Gudang</p>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900">Ringkasan Gudang</h2>
                    <p class="max-w-2xl text-sm text-slate-500">Metrik stok dan aktivitas transaksi hari ini dalam tampilan yang lebih rapi.</p>
                </div>
                <div class="inline-flex items-center gap-3 rounded-full bg-slate-100 px-4 py-3 text-sm text-slate-600 shadow-sm">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-200 text-slate-700">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </span>
                    <div>
                        <div class="text-xs uppercase tracking-[0.25em] text-slate-500">Inventaris</div>
                        <div class="text-sm font-semibold text-slate-900">Stok real-time</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            @livewire(\App\Filament\Widgets\RingkasanStok::class)
        </div>
    </section>
</div>