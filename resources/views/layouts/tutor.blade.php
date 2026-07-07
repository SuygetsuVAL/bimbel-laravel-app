<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>@yield('title', 'AozoraLearn') — Tutor Portal</title>
    
    <!-- Tailwind CSS with Forms & Container Queries plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
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
    </style>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-on-surface": "#ebf1ff",
                        "background": "#f9f9ff",
                        "on-primary-fixed-variant": "#003ea8",
                        "surface-tint": "#0053db",
                        "on-error": "#ffffff",
                        "surface-dim": "#d0daef",
                        "on-tertiary-fixed": "#301400",
                        "primary-container": "#2563eb",
                        "gray-900": "#111827",
                        "inverse-primary": "#b4c5ff",
                        "secondary-container": "#fd761a",
                        "surface-container-high": "#dee9fd",
                        "surface-variant": "#d9e3f7",
                        "primary": "#004ac6", // Blue theme to match admin
                        "primary-tint": "#EFF6FF",
                        "on-tertiary": "#ffffff",
                        "surface-bright": "#f9f9ff",
                        "secondary-dark": "#EA580C",
                        "primary-fixed-dim": "#b4c5ff",
                        "on-surface": "#121c2a",
                        "warning": "#F59E0B",
                        "gray-50": "#F9FAFB",
                        "on-primary-fixed": "#00174b",
                        "primary-dark": "#1E40AF",
                        "secondary": "#9d4300",
                        "surface-container-low": "#eff3ff",
                        "secondary-fixed-dim": "#ffb690",
                        "on-secondary-fixed-variant": "#783200",
                        "tertiary": "#864300",
                        "surface-container-highest": "#d9e3f7",
                        "error": "#EF4444",
                        "on-primary-container": "#eeefff",
                        "error-container": "#ffdad6",
                        "primary-light": "#3B82F6",
                        "surface-container": "#e6eeff",
                        "tertiary-fixed-dim": "#ffb783",
                        "on-tertiary-fixed-variant": "#713700",
                        "outline": "#737686",
                        "outline-variant": "#c3c6d7",
                        "secondary-fixed": "#ffdbca",
                        "on-error-container": "#93000a",
                        "gray-100": "#F3F4F6",
                        "on-surface-variant": "#434655",
                        "on-background": "#121c2a",
                        "on-secondary": "#ffffff",
                        "on-tertiary-container": "#ffede3",
                        "on-secondary-container": "#5c2400",
                        "inverse-surface": "#273140",
                        "gray-500": "#6B7280",
                        "surface-container-lowest": "#ffffff",
                        "gray-300": "#D1D5DB",
                        "on-secondary-fixed": "#341100",
                        "tertiary-container": "#aa5700",
                        "on-primary": "#ffffff",
                        "surface": "#f9f9ff",
                        "tertiary-fixed": "#ffdcc5",
                        "primary-fixed": "#dbe1ff",
                        "success": "#10B981"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-desktop": "24px",
                        "base": "4px",
                        "stack-md": "16px",
                        "margin-mobile": "16px",
                        "gutter-md": "16px",
                        "stack-lg": "24px",
                        "stack-sm": "8px"
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-on-surface font-body-md min-h-screen flex">

<!-- SideNavBar -->
<aside class="hidden lg:flex flex-col w-[280px] h-screen fixed left-0 top-0 py-8 bg-surface-container-lowest border-r border-gray-100 z-40">
    <div class="px-6 mb-6 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold text-lg">T</div>
        <div>
            <h1 class="text-headline-sm font-bold text-primary tracking-tight">AozoraLearn</h1>
            <p class="text-label-sm text-gray-500">Tutor Portal</p>
        </div>
    </div>
    
    <!-- User Profile Details -->
    <div class="px-6 mb-6 flex items-center gap-3 border-b border-gray-100 pb-6">
        <div class="w-12 h-12 rounded-full overflow-hidden bg-primary-tint border border-primary/20 flex items-center justify-center">
            <span class="text-primary font-bold text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
        </div>
        <div>
            <p class="text-label-md font-bold text-gray-900 leading-none mb-1">{{ auth()->user()->name }}</p>
            <p class="text-[10px] text-gray-500 uppercase font-semibold">{{ auth()->user()->tutor->specialization ?? 'Pengajar' }}</p>
        </div>
    </div>

    <!-- Links -->
    <nav class="flex-grow px-2 space-y-1">
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('tutor.dashboard') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }} transition-all" href="{{ route('tutor.dashboard') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('tutor.dashboard') ? '1' : '0' }};">dashboard</span>
            <span class="text-label-md font-medium">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('tutor.attendance.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }} transition-all" href="{{ route('tutor.attendance.index') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('tutor.attendance.*') ? '1' : '0' }};">how_to_reg</span>
            <span class="text-label-md font-medium">Presensi & Kehadiran</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('tutor.materials.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }} transition-all" href="{{ route('tutor.materials.index') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('tutor.materials.*') ? '1' : '0' }};">menu_book</span>
            <span class="text-label-md font-medium">Materi Pelajaran</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('tutor.grades.*') ? 'bg-primary-tint text-primary border-l-4 border-primary font-bold' : 'text-gray-500 hover:bg-gray-50' }} transition-all" href="{{ route('tutor.grades.index') }}">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' {{ request()->routeIs('tutor.grades.*') ? '1' : '0' }};">grading</span>
            <span class="text-label-md font-medium">Input & Rekap Nilai</span>
        </a>
    </nav>

    <!-- Logout -->
    <div class="mt-auto px-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 border border-red-500 text-red-500 hover:bg-red-50 font-medium rounded-lg transition-colors text-sm">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Keluar
            </button>
        </form>
    </div>
</aside>

<!-- Main Content Canvas -->
<main class="flex-1 lg:ml-[280px] p-6 lg:p-8 w-full max-w-[1440px] mx-auto flex flex-col justify-between min-h-screen">
    <div>
        <!-- Flash Alert -->
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

    <!-- Footer -->
    <footer class="py-8 border-t border-gray-100 w-full mt-12 bg-white rounded-xl p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <p class="text-label-md font-bold text-primary">AozoraLearn</p>
                <p class="text-body-sm text-gray-500">© 2026 AozoraLearn Education System. Hak Cipta Dilindungi.</p>
            </div>
            <div class="flex gap-6 text-sm text-gray-500">
                <a href="#" class="hover:text-primary">Kebijakan Privasi</a>
                <a href="#" class="hover:text-primary">Syarat Ketentuan</a>
            </div>
        </div>
    </footer>
</main>

@stack('scripts')
</body>
</html>
