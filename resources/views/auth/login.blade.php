<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — AozoraLearn</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    
    <!-- Google Fonts & Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Decorative background blobs -->
    <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] rounded-full bg-blue-400/20 blur-3xl"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] rounded-full bg-orange-400/20 blur-3xl"></div>

    <div class="w-full max-w-md glass-card rounded-2xl p-8 shadow-2xl relative z-10">
        <!-- Logo and Heading -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 mb-3">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-md">
                    A
                </div>
                <span class="text-2xl font-bold text-slate-800 tracking-tight">AozoraLearn</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mt-2">Selamat Datang Kembali</h2>
            <p class="text-sm text-slate-500 mt-1">Silakan masuk ke akun portal Anda.</p>
        </div>

        <!-- Success & Error Alert Messages -->
        @if(session('success'))
            <div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-green-600 text-sm">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-red-600 text-sm">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-2">Alamat Email *</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">mail</span>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="contoh@aozoralearn.id" class="w-full pl-10 pr-4 py-2.5 bg-white/50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-xs font-semibold text-slate-700">Kata Sandi *</label>
                </div>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">lock</span>
                    <input type="password" id="password" name="password" required placeholder="••••••••" class="w-full pl-10 pr-4 py-2.5 bg-white/50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600 font-medium">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500/20">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-lg shadow-blue-600/20 hover:shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">login</span>
                Masuk ke Portal
            </button>
        </form>

        <!-- Informative Footer -->
        <div class="mt-8 text-center border-t border-slate-200/50 pt-6">
            <p class="text-xs text-slate-400 font-medium">© 2026 AozoraLearn. All Rights Reserved.</p>
        </div>
    </div>
</body>
</html>
