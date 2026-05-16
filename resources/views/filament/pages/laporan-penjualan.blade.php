<div class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-12">
        <div class="lg:col-span-12">
            <x-filament-widgets::widgets
                :columns="['default' => 1, 'md' => 2, 'xl' => 4]"
                :data="[]"
                :widgets="[\App\Filament\Widgets\LaporanPenjualanOmzet::class]"
            />
        </div>

        <div class="lg:col-span-12">
            <x-filament-widgets::widgets
                :columns="['default' => 1, 'md' => 2]"
                :data="[]"
                :widgets="[\App\Filament\Widgets\LaporanPenjualanChart::class]"
            />
        </div>
    </div>
</div>
