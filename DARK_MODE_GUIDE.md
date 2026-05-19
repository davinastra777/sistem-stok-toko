# 🌙 Dark Mode Toggle Implementation Guide

Panduan untuk mengintegrasikan Dark Mode Toggle ke dalam aplikasi Anda.

## Lokasi File

Komponen dark mode toggle sudah dibuat di:
```
resources/views/components/dark-mode-toggle.blade.php
```

## Cara Menggunakan

### 1. Di Welcome Page
Edit `resources/views/welcome.blade.php` dan tambahkan komponen di navbar:

```html
<!-- Di bagian navbar, sebelum login/register buttons -->
<nav class="sticky top-0 z-50 backdrop-blur-lg bg-white/80 dark:bg-neutral-950/80 border-b border-neutral-200 dark:border-neutral-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            ...

            <!-- Navigation Links -->
            <div class="flex items-center gap-4">
                <!-- Dark Mode Toggle -->
                @include('components.dark-mode-toggle')

                <!-- Login/Register Buttons -->
                @if (Route::has('login'))
                    ...
                @endif
            </div>
        </div>
    </div>
</nav>
```

### 2. Di Filament Panel
Jika Anda ingin menambahkan dark mode toggle di Filament admin panel, edit Filament layout atau membuat custom widget.

Edit `app/Providers/Filament/AdminPanelProvider.php`:

```php
// Filament sudah support dark mode secara native
// Dark mode akan otomatis tersedia di admin panel
// User bisa toggle via system preferences atau Filament theme selector
```

### 3. Di Halaman Custom
Untuk halaman custom lainnya:

```blade
<!DOCTYPE html>
<html class="scroll-smooth">
<head>
    <!-- ... head content ... -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white dark:bg-neutral-950">
    <!-- Navigation dengan Dark Mode Toggle -->
    <nav>
        <div class="flex justify-between items-center">
            <h1>My App</h1>
            @include('components.dark-mode-toggle')
        </div>
    </nav>

    <!-- Main content -->
    <main class="bg-neutral-50 dark:bg-neutral-900">
        <!-- Konten halaman -->
    </main>
</body>
</html>
```

## Fitur Dark Mode Toggle

### Keyboard Shortcut
```
Ctrl + Shift + L = Toggle Dark Mode
```

### Storage
- Theme preference disimpan di localStorage
- Auto-deteksi system preference (OS dark mode)
- Persisten antar session

### Behavior
- Smooth transition antar mode
- SVG icons berubah secara otomatis
- Tooltip helper saat hover

## Styling Dark Mode

### Untuk semua elemen, gunakan prefix `dark:`
```html
<!-- Light mode: white background -->
<!-- Dark mode: dark background -->
<div class="bg-white dark:bg-neutral-900">
    Content
</div>
```

### Color Examples
```html
<!-- Text color responsif ke dark mode -->
<p class="text-neutral-900 dark:text-neutral-100">
    Teks yang berubah sesuai mode
</p>

<!-- Button dengan dark mode support -->
<button class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-700 dark:hover:bg-primary-600">
    Button
</button>

<!-- Card dengan dark mode -->
<div class="bg-white dark:bg-neutral-800 rounded-lg shadow-md">
    Card content
</div>
```

## Troubleshooting

### Dark Mode tidak ter-apply
1. Pastikan file `app.css` sudah ter-compile
2. Check browser console untuk errors
3. Clear cache browser (Ctrl+Shift+Delete)
4. Restart dev server: `npm run dev`

### SVG Icons tidak terlihat
1. Pastikan SVG icons memiliki `fill="currentColor"`
2. Check color classes (text-yellow-500, text-neutral-700)
3. Verify dark: prefix syntax

### localStorage tidak bekerja
1. Check apakah browser mengizinkan localStorage
2. Pastikan website tidak di-block untuk storage
3. Try incognito mode untuk test

### Transition tidak smooth
1. Pastikan tidak ada `prefers-reduced-motion: reduce`
2. Check CSS transition rules
3. Verify HTML memiliki `class="scroll-smooth"`

## Customization

### Mengubah Icons
Edit di `resources/views/components/dark-mode-toggle.blade.php`:

```html
<!-- Sun Icon SVG - diganti icon favorite Anda -->
<svg id="sunIcon" class="...">
    <!-- Ganti dengan icon pilihan -->
</svg>

<!-- Moon Icon SVG - diganti icon favorite Anda -->
<svg id="moonIcon" class="...">
    <!-- Ganti dengan icon pilihan -->
</svg>
```

### Mengubah Tooltip
```html
<div id="themeTooltip" ...>
    Kustomisasi teks tooltip di sini
</div>
```

### Mengubah Posisi
Default: top-right dari button. Untuk mengubah:

```html
<!-- Ganti class pada tooltip div -->
<!-- Dari: bottom-full right-0 -->
<!-- Ke: bottom-full left-0 atau top-full right-0 dll -->
<div class="absolute top-full left-0 ...">
    Toggle Dark Mode
</div>
```

### Mengubah Keyboard Shortcut
Edit di script section:

```javascript
// Ganti keyCombination (sekarang: Ctrl+Shift+L)
document.addEventListener('keydown', (e) => {
    if (e.ctrlKey && e.altKey && e.key === 'D') {  // Ctrl+Alt+D
        e.preventDefault();
        toggleTheme();
    }
});
```

## Integrasi dengan Filament

Filament v3 sudah memiliki dark mode support built-in. Untuk mengakses:

1. **Di Admin Panel**: User bisa menggunakan system theme preference
2. **Untuk custom toggle di Filament**: Buat Filament action atau widget

Contoh:
```php
// app/Filament/Pages/Dashboard.php
use Filament\Support\Facades\FilamentIcon;

// Dark mode akan automatically work di Filament
```

## Best Practices

✅ **Selalu gunakan `dark:` prefix** untuk styling yang berbeda di dark mode
✅ **Test di both modes** sebelum deploy
✅ **Gunakan semantic colors** (primary, success, warning, danger)
✅ **Contrast ratio** minimal WCAG AA (4.5:1 untuk teks)
✅ **Prefer system preference** daripada force mode

## Testing

### Manual Testing
1. Toggle dark mode via button
2. Refresh halaman - mode tetap tersimpan
3. Change OS theme - auto detect berfungsi
4. Test keyboard shortcut (Ctrl+Shift+L)
5. Test di berbagai browser

### Browser Support
- ✅ Chrome/Chromium 76+
- ✅ Firefox 67+
- ✅ Safari 12.1+
- ✅ Edge 79+
- ✅ Mobile browsers (iOS Safari, Chrome Mobile)

## Resources

- [MDN: prefers-color-scheme](https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-color-scheme)
- [Tailwind Dark Mode](https://tailwindcss.com/docs/dark-mode)
- [Web.dev: Prefers-color-scheme](https://web.dev/prefers-color-scheme/)

---

**Happy dark mode coding! 🌙**
