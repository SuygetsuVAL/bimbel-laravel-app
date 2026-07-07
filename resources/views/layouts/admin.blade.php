<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'AozoraLearn') — Admin Portal</title>
    
    <!-- Tailwind CSS with Forms & Container Queries plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- NProgress CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9f9ff;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 16px;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        /* ===== PAGE TRANSITION ANIMATIONS ===== */
        #nprogress .bar {
            background: #004ac6 !important;
            height: 3px !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px #004ac6, 0 0 5px #004ac6 !important;
        }
        #nprogress .spinner-icon {
            border-top-color: #004ac6 !important;
            border-left-color: #004ac6 !important;
        }

        /* Fade + slide-up saat halaman masuk */
        @keyframes pageEnter {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .page-content {
            animation: pageEnter 0.35s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        /* Fade-out saat klik link (keluar halaman) */
        @keyframes pageFadeOut {
            from { opacity: 1; transform: translateY(0); }
            to   { opacity: 0; transform: translateY(-8px); }
        }
        .page-leaving {
            animation: pageFadeOut 0.2s ease-in forwards;
            pointer-events: none;
        }

        /* Ripple effect pada link sidebar */
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(0, 74, 198, 0.18);
            transform: scale(0);
            animation: rippleAnim 0.55s linear;
            pointer-events: none;
        }
        @keyframes rippleAnim {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "error-container": "#ffdad6",
                        "surface-variant": "#d9e3f7",
                        "secondary": "#9d4300",
                        "inverse-on-surface": "#ebf1ff",
                        "primary-tint": "#EFF6FF",
                        "secondary-dark": "#EA580C",
                        "surface-container": "#e6eeff",
                        "on-surface-variant": "#434655",
                        "secondary-container": "#fd761a",
                        "success": "#10B981",
                        "on-tertiary-fixed-variant": "#713700",
                        "gray-500": "#6B7280",
                        "primary-dark": "#1E40AF",
                        "primary-fixed": "#dbe1ff",
                        "surface-dim": "#d0daef",
                        "secondary-fixed": "#ffdbca",
                        "inverse-primary": "#b4c5ff",
                        "gray-900": "#111827",
                        "gray-50": "#F9FAFB",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#121c2a",
                        "surface-container-low": "#eff3ff",
                        "gray-300": "#D1D5DB",
                        "error": "#EF4444",
                        "surface-tint": "#0053db",
                        "on-secondary": "#ffffff",
                        "on-error-container": "#93000a",
                        "on-secondary-container": "#5c2400",
                        "inverse-surface": "#273140",
                        "on-primary-fixed-variant": "#003ea8",
                        "tertiary-fixed-dim": "#ffb783",
                        "on-secondary-fixed": "#341100",
                        "on-tertiary-container": "#ffede3",
                        "on-tertiary": "#ffffff",
                        "surface-container-highest": "#d9e3f7",
                        "primary": "#004ac6",
                        "on-primary-container": "#eeefff",
                        "on-error": "#ffffff",
                        "primary-fixed-dim": "#b4c5ff",
                        "primary-container": "#2563eb",
                        "tertiary": "#864300",
                        "warning": "#F59E0B",
                        "outline-variant": "#c3c6d7",
                        "gray-100": "#F3F4F6",
                        "background": "#f9f9ff",
                        "surface-container-high": "#dee9fd",
                        "outline": "#737686",
                        "on-background": "#121c2a",
                        "tertiary-fixed": "#ffdcc5",
                        "surface-bright": "#f9f9ff",
                        "on-secondary-fixed-variant": "#783200",
                        "tertiary-container": "#aa5700",
                        "on-tertiary-fixed": "#301400",
                        "on-primary": "#ffffff",
                        "secondary-fixed-dim": "#ffb690",
                        "primary-light": "#3B82F6",
                        "on-primary-fixed": "#00174b",
                        "surface": "#f9f9ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "base": "4px",
                        "stack-lg": "24px",
                        "margin-mobile": "16px",
                        "gutter-md": "16px",
                        "margin-desktop": "24px",
                        "stack-sm": "8px",
                        "stack-md": "16px"
                    },
                    "fontFamily": {
                        "headline-lg": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-sm": ["Inter"],
                        "body-sm": ["Inter"],
                        "body-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "label-md": ["Inter"]
                    },
                    "fontSize": {
                        "headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "700"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                        "label-md": ["14px", {"lineHeight": "20px", "fontWeight": "600"}]
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-background text-on-background antialiased overflow-hidden">

<!-- Side Navigation Bar -->
<aside class="fixed left-0 top-0 h-full w-[280px] bg-surface-container-lowest shadow-sm border-r border-outline-variant flex flex-col z-50">
    <div class="p-6">
        <h1 class="text-headline-sm font-bold text-primary tracking-tight">AozoraLearn</h1>
        <p class="text-label-sm text-outline">Admin Portal</p>
    </div>
    <nav class="flex-1 px-3 space-y-1">
        <!-- Dashboard Link -->
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-gray-100' }} rounded-lg transition-all cursor-pointer group" href="{{ route('admin.dashboard') }}">
            <span class="material-symbols-outlined {{ request()->routeIs('admin.dashboard') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.dashboard') ? '1' : '0' }};">dashboard</span>
            <span class="font-label-md text-label-md">Dashboard</span>
        </a>
        <!-- Student Link -->
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.students.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-gray-100' }} rounded-lg transition-all cursor-pointer group" href="{{ route('admin.students.index') }}">
            <span class="material-symbols-outlined {{ request()->routeIs('admin.students.*') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.students.*') ? '1' : '0' }};">group</span>
            <span class="font-label-md text-label-md">Data Siswa</span>
        </a>
        <!-- Tutor Link -->
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.tutors.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-gray-100' }} rounded-lg transition-all cursor-pointer group" href="{{ route('admin.tutors.index') }}">
            <span class="material-symbols-outlined {{ request()->routeIs('admin.tutors.*') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.tutors.*') ? '1' : '0' }};">school</span>
            <span class="font-label-md text-label-md">Data Tutor</span>
        </a>
        <!-- Class Link -->
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.classes.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-gray-100' }} rounded-lg transition-all cursor-pointer group" href="{{ route('admin.classes.index') }}">
            <span class="material-symbols-outlined {{ request()->routeIs('admin.classes.*') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.classes.*') ? '1' : '0' }};">class</span>
            <span class="font-label-md text-label-md">Kelas</span>
        </a>
        <!-- Schedule Link -->
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.schedules.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-gray-100' }} rounded-lg transition-all cursor-pointer group" href="{{ route('admin.schedules.index') }}">
            <span class="material-symbols-outlined {{ request()->routeIs('admin.schedules.*') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.schedules.*') ? '1' : '0' }};">calendar_today</span>
            <span class="font-label-md text-label-md">Jadwal</span>
        </a>
        <!-- Payment Link -->
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.payments.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-gray-100' }} rounded-lg transition-all cursor-pointer group" href="{{ route('admin.payments.index') }}">
            <span class="material-symbols-outlined {{ request()->routeIs('admin.payments.*') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.payments.*') ? '1' : '0' }};">payments</span>
            <span class="font-label-md text-label-md">Keuangan</span>
        </a>
        <!-- Report Link -->
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.reports.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-gray-100' }} rounded-lg transition-all cursor-pointer group" href="{{ route('admin.reports.index') }}">
            <span class="material-symbols-outlined {{ request()->routeIs('admin.reports.*') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.reports.*') ? '1' : '0' }};">analytics</span>
            <span class="font-label-md text-label-md">Laporan</span>
        </a>
        <!-- Settings / Notification Link -->
        <a class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.notifications.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-on-surface-variant hover:bg-gray-100' }} rounded-lg transition-all cursor-pointer group" href="{{ route('admin.notifications.index') }}">
            <span class="material-symbols-outlined {{ request()->routeIs('admin.notifications.*') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}" style="font-variation-settings: 'FILL' {{ request()->routeIs('admin.notifications.*') ? '1' : '0' }};">campaign</span>
            <span class="font-label-md text-label-md">Pengumuman</span>
        </a>
    </nav>
    
    <!-- User profile bottom info & logout -->
    <div class="p-6 border-t border-outline-variant">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-primary-fixed flex items-center justify-center">
                <span class="text-primary font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
            </div>
            <div>
                <p class="text-label-md font-bold text-on-surface">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-outline uppercase tracking-wider">Super Administrator</p>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 border border-error text-error hover:bg-red-50 font-label-md rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Keluar
            </button>
        </form>
    </div>
</aside>

<!-- Main Content Area -->
<main class="ml-[280px] h-screen overflow-y-auto custom-scrollbar flex flex-col justify-between">
    <div>
        <!-- Top Navigation Bar -->
        <header class="h-16 sticky top-0 bg-surface shadow-sm px-6 flex justify-between items-center z-40 border-b border-outline-variant">
            <div class="flex items-center bg-surface-container-low px-4 py-2 rounded-full w-96 group focus-within:ring-2 focus-within:ring-primary/20 transition-all">
                <span class="material-symbols-outlined text-outline group-focus-within:text-primary">search</span>
                <input class="bg-transparent border-none focus:ring-0 text-body-sm w-full ml-2" placeholder="Cari data, siswa, atau kelas..." type="text">
            </div>
            <div class="flex items-center gap-4">
                <button class="relative hover:bg-surface-variant rounded-full p-2 transition-all active:opacity-80">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full"></span>
                </button>
                <span class="text-label-md font-semibold text-outline">Admin Panel</span>
            </div>
        </header>

        <!-- Dynamic Content Canvas -->
        <div class="p-8 max-w-[1440px] mx-auto page-content">
            <!-- Flash Message Banner -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600">check_circle</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-red-600">error</span>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Layout Footer -->
    <footer class="py-8 border-t border-outline-variant px-8 w-full bg-white">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 max-w-[1440px] mx-auto">
            <div class="flex flex-col items-center md:items-start">
                <p class="text-label-md font-bold text-primary">AozoraLearn</p>
                <p class="text-body-sm text-outline">© 2026 AozoraLearn Education System. Hak Cipta Dilindungi.</p>
            </div>
            <div class="flex gap-6">
                <a href="#" class="text-label-sm text-outline hover:text-primary transition-colors">Kebijakan Privasi</a>
                <a href="#" class="text-label-sm text-outline hover:text-primary transition-colors">Syarat & Ketentuan</a>
                <a href="#" class="text-label-sm text-outline hover:text-primary transition-colors">Pusat Bantuan</a>
            </div>
        </div>
    </footer>
</main>

<!-- NProgress JS -->
<script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>

<!-- Interactions & Page Transition Script -->
<script>
    // ===== NPROGRESS CONFIG =====
    NProgress.configure({ showSpinner: false, speed: 400, minimum: 0.1 });

    // ===== PAGE ENTER ANIMATION =====
    document.addEventListener('DOMContentLoaded', () => {
        NProgress.done();

        const pageContent = document.querySelector('.page-content');
        if (pageContent) {
            pageContent.style.animationDuration = '0.35s';
        }

        // ===== LINK CLICK: loading bar + fade-out =====
        document.querySelectorAll('a[href]').forEach(link => {
            // Skip link yang buka tab baru, anchor, atau javascript
            if (
                link.target === '_blank' ||
                link.href.startsWith('javascript') ||
                link.href.includes('#') ||
                link.getAttribute('href') === '#'
            ) return;

            // Ripple effect
            link.classList.add('nav-link');
            link.addEventListener('click', function(e) {
                // Ripple
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                ripple.className = 'ripple';
                ripple.style.width  = ripple.style.height = size + 'px';
                ripple.style.left   = (e.clientX - rect.left - size / 2) + 'px';
                ripple.style.top    = (e.clientY - rect.top  - size / 2) + 'px';
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);

                // Start NProgress & fade-out page
                NProgress.start();
                if (pageContent) pageContent.classList.add('page-leaving');
            });
        });

        // Form submit juga trigger NProgress
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                NProgress.start();
                if (pageContent) pageContent.classList.add('page-leaving');
            });
        });

        // ===== SEARCH BAR INTERACTION =====
        const searchInput = document.querySelector('input[placeholder="Cari data, siswa, atau kelas..."]');
        if (searchInput) {
            const searchContainer = searchInput.parentElement;
            searchInput.addEventListener('focus', () => searchContainer.classList.add('shadow-md'));
            searchInput.addEventListener('blur',  () => searchContainer.classList.remove('shadow-md'));
        }
    });

    // Trigger NProgress saat halaman mulai dimuat
    window.addEventListener('beforeunload', () => NProgress.start());
</script>
@stack('scripts')
</body>
</html>
