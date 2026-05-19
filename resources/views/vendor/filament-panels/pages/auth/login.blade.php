<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 p-4">
    <div class="w-full max-w-md">

        {{-- Logo / Branding --}}
        <div class="text-center mb-8">
            <div class="mb-4">
                <img 
                    src="{{ asset('images/logo.png') }}" 
                    alt="Logo Toko Kerupuk Kemplang HG"
                    class="h-20 w-auto mx-auto object-contain"
                >
            </div>
            <h1 class="text-xl font-bold text-slate-800">Toko Kerupuk Kemplang HG</h1>
            <p class="text-sm text-slate-500 mt-1">Sistem Manajemen Toko</p>
        </div>

        {{-- Card Login --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 ring-1 ring-slate-100 p-8">
            <h2 class="text-2xl font-bold text-slate-900 mb-1">Masuk</h2>
            <p class="text-sm text-slate-500 mb-6">Masukkan email dan kata sandi Anda</p>

            <x-filament-panels::form id="form" wire:submit="authenticate">
                {{ $this->form }}

                <x-filament::button
                    type="submit"
                    class="mt-6 w-full"
                    size="lg"
                    style="background: linear-gradient(135deg, #f97316, #f59e0b); border: none;"
                >
                    Masuk ke Sistem
                </x-filament::button>
            </x-filament-panels::form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; {{ date('Y') }} Toko Kerupuk Kemplang HG
        </p>
    </div>
</div>