<div class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-12">
            <x-filament-widgets::widgets
                :columns="['default' => 1, 'md' => 2]"
                :data="[]"
                :widgets="[\App\Filament\Widgets\StokMasukChart::class, \App\Filament\Widgets\LaporanStokKeluarChart::class]"
            />
        </div>

        <div class="lg:col-span-12">
            <x-filament-widgets::widgets
                :columns="['default' => 1]"
                :data="[]"
                :widgets="[\App\Filament\Widgets\LaporanStokList::class]"
            />
        </div>
    </div>
</div>
