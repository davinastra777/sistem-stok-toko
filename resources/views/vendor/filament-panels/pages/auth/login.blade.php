<x-filament-panels::page.simple>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 p-4">
        <div class="w-full max-w-md">

        {{-- Logo / Branding --}}
        <div class="text-center mb-8">
            <div class="mb-4">
                <img 
                    src="{{ asset('images/logo.png') }}" 
                    alt="Logo Toko Kerupuk Kemplang HG"
                    class="h-15 w-auto mx-auto object-contain"
                >
            </div>
            <h1 class="text-xl font-bold text-slate-800">Toko Kerupuk Kemplang HG</h1>
        </div>

        {{-- Card Login --}}
        <div class="bg-white rounded-3xl shadow-xl ring-1 ring-slate-100 p-8">


            <x-filament-panels::form id="form" wire:submit="authenticate">
                <div class="space-y-4">
                    {{ $this->form }}

                    <x-filament::button
                        type="submit"
                        class="mt-2 w-full bg-gradient-to-r from-amber-500 to-orange-500 text-white hover:from-amber-600 hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-amber-300"
                        size="lg"
                    >
                        Masuk ke Sistem
                    </x-filament::button>
                </div>
            </x-filament-panels::form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; {{ date('Y') }} Toko Kerupuk Kemplang HG
        </p>
    </div>
</div>
</x-filament-panels::page.simple>