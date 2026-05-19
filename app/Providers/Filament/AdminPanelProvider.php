<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\Dashboard;
use Filament\Pages;
use Filament\Pages\Auth\Login;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName('Toko Kerupuk Kemplang HG')
            ->brandLogoHeight('2rem')
            ->favicon(asset('images/favicon.ico'))
            ->colors([
                'primary' => Color::Sky,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'danger' => Color::Rose,
                'gray' => Color::Slate,
            ])
            ->darkMode(false)
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): HtmlString => new HtmlString('
                    <style>
                        /* 1. Background Sidebar warna Navy */
                        aside.fi-sidebar { 
                            background-color: #1e3a8a !important; 
                        }
                        
                        /* 2. Judul Kelompok Menu (Master Data, Transaksi, dll) */
                        aside.fi-sidebar .fi-sidebar-group-label {
                            color: #93c5fd !important; 
                        }

                        /* 3. Warna teks & icon menu biasa (Putih abu-abu lembut) */
                        aside.fi-sidebar .fi-sidebar-item-button .fi-sidebar-item-label,
                        aside.fi-sidebar .fi-sidebar-item-button .fi-sidebar-item-icon { 
                            color: #e2e8f0 !important; 
                        }

                        /* 4. Efek hover saat menu biasa disentuh mouse (Biru agak terang) */
                        aside.fi-sidebar .fi-sidebar-item-button:hover { 
                            background-color: #1e40af !important; 
                        }
                        aside.fi-sidebar .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                        aside.fi-sidebar .fi-sidebar-item-button:hover .fi-sidebar-item-icon { 
                            color: #ffffff !important; 
                        }

                        /* 5. Menu AKTIF secara bawaan dibuat polos transparan */
                        aside.fi-sidebar .fi-sidebar-item-button[aria-current="page"] {
                            background-color: transparent !important; 
                            box-shadow: none !important; 
                        }
                        aside.fi-sidebar .fi-sidebar-item-button[aria-current="page"] .fi-sidebar-item-label,
                        aside.fi-sidebar .fi-sidebar-item-button[aria-current="page"] .fi-sidebar-item-icon {
                            color: #ffffff !important; 
                            font-weight: 700 !important; 
                        }

                        /* KUNCI PERBAIKAN: Saat menu AKTIF disentuh kursor, berikan warna hover yang sama! */
                        aside.fi-sidebar .fi-sidebar-item-button[aria-current="page"]:hover {
                            background-color: #1e40af !important; 
                        }
                        
                        /* 6. Background halaman utama (Abu-abu sangat terang) */
                        main.fi-main { 
                            background-color: #f8fafc !important; 
                        }
                    </style>
                '),
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
                \App\Filament\Pages\TransactionHistory::class,
            ])
            ->navigationGroups([
                'Dashboard',
                'Master Data',
                'Transaksi',
                'Laporan',
            ])
            ->widgets([
                \App\Filament\Widgets\LaporanPenjualanOmzet::class,
                \App\Filament\Widgets\RingkasanStok::class,
                \App\Filament\Widgets\ProdukStokMenipis::class,
                \App\Filament\Widgets\LaporanPenjualanChart::class,
                \App\Filament\Widgets\StokMasukChart::class,
                \App\Filament\Widgets\LaporanStokKeluarChart::class,
                \App\Filament\Widgets\LaporanStokList::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}