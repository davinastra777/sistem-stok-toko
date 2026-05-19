<!-- Dark Mode Toggle Component -->
<!-- Letakkan di navbar atau header aplikasi Anda -->

<div id="themeToggle" class="relative inline-block">
    <button 
        id="themeToggleBtn"
        class="p-2 rounded-lg hover:bg-neutral-200 dark:hover:bg-neutral-700 transition-colors"
        aria-label="Toggle dark mode"
        title="Toggle Dark Mode (Ctrl+Shift+L)"
    >
        <!-- Sun Icon (shown in dark mode) -->
        <svg 
            id="sunIcon"
            class="w-5 h-5 text-yellow-500 hidden dark:inline"
            fill="currentColor"
            viewBox="0 0 24 24"
        >
            <path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/>
            <path d="M12 5a1 1 0 0 1 1 1v2a1 1 0 0 1-2 0V6a1 1 0 0 1 1-1zm7 6a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-2a1 1 0 0 0-1-1zm-14 0a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-2a1 1 0 0 0-1-1zm11.99-3.99a1 1 0 0 0-1.41 0L15.17 6.58a1 1 0 1 0 1.41 1.41l1.41-1.41a1 1 0 0 0 0-1.41zM6.58 15.17a1 1 0 0 0-1.41 1.41l1.41 1.41a1 1 0 0 0 1.41-1.41l-1.41-1.41zm10.83-4.58a1 1 0 0 0-1.41 0l-1.41 1.41a1 1 0 0 0 1.41 1.41l1.41-1.41a1 1 0 0 0 0-1.41z" style="transform: scale(-1, 1)"/>
        </svg>

        <!-- Moon Icon (shown in light mode) -->
        <svg 
            id="moonIcon"
            class="w-5 h-5 text-neutral-700 inline dark:hidden"
            fill="currentColor"
            viewBox="0 0 24 24"
        >
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
    </button>

    <!-- Tooltip -->
    <div 
        class="absolute bottom-full right-0 mb-2 px-3 py-1 bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 text-xs rounded opacity-0 pointer-events-none transition-opacity whitespace-nowrap"
        id="themeTooltip"
    >
        Toggle Dark Mode
    </div>
</div>

<script>
    // Dark Mode Toggle Script
    (function() {
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const themeTooltip = document.getElementById('themeTooltip');
        const html = document.documentElement;

        // Initialize theme dari localStorage
        function initializeTheme() {
            const stored = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (stored === 'dark' || (!stored && prefersDark)) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }

        // Toggle theme
        function toggleTheme() {
            const isDark = html.classList.contains('dark');
            
            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Show tooltip
        function showTooltip() {
            themeTooltip.classList.remove('opacity-0');
            themeTooltip.classList.add('opacity-100');
        }

        // Hide tooltip
        function hideTooltip() {
            themeTooltip.classList.add('opacity-0');
            themeTooltip.classList.remove('opacity-100');
        }

        // Event listeners
        themeToggleBtn.addEventListener('click', toggleTheme);
        themeToggleBtn.addEventListener('mouseenter', showTooltip);
        themeToggleBtn.addEventListener('mouseleave', hideTooltip);

        // Keyboard shortcut: Ctrl+Shift+L
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey && e.key === 'L') {
                e.preventDefault();
                toggleTheme();
            }
        });

        // Detect system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                if (e.matches) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            }
        });

        // Initialize on page load
        initializeTheme();
    })();
</script>

<style>
    /* Smooth transition untuk dark mode */
    @media (prefers-reduced-motion: no-preference) {
        html {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        html.dark {
            color-scheme: dark;
        }

        html:not(.dark) {
            color-scheme: light;
        }
    }
</style>
