@extends('layouts.admin')
@section('title', 'Data Tutor')

@section('content')
<!-- Page Header Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h3 class="text-headline-md text-on-surface mb-1">Manajemen Tutor</h3>
        <p class="text-body-sm text-outline">Kelola data seluruh tutor pengajar AozoraLearn.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.tutors.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-label-md rounded-lg hover:bg-primary-dark transition-all shadow-md active:scale-95 text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Tutor
        </a>
    </div>
</div>

{{-- Flash: Info akun tutor baru --}}
@if(session('success') && str_contains(session('success'), 'Akun login:'))
    @php
        // Parse "Tutor berhasil ditambahkan. Akun login: desi@aozoralearn.id / tutor123"
        preg_match('/Akun login:\s*(\S+)\s*\/\s*(\S+)/', session('success'), $m);
        $newEmail    = $m[1] ?? '';
        $newPassword = $m[2] ?? '';
    @endphp
    <div class="mb-6 p-5 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-300 rounded-xl" id="accountInfoBox">
        <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-green-600 text-[28px] mt-0.5" style="font-variation-settings:'FILL' 1;">check_circle</span>
            <div class="flex-1">
                <p class="font-bold text-green-800 text-sm mb-3">✅ Tutor berhasil ditambahkan! Berikut info akun login-nya:</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex items-center justify-between bg-white border border-green-200 rounded-lg px-4 py-3 group">
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-0.5">📧 Email Login</p>
                            <p class="font-mono font-bold text-gray-900 text-sm" id="copyEmail">{{ $newEmail }}</p>
                        </div>
                        <button onclick="copyText('{{ $newEmail }}', this)" title="Salin email"
                                class="ml-3 text-gray-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">content_copy</span>
                        </button>
                    </div>
                    <div class="flex items-center justify-between bg-white border border-green-200 rounded-lg px-4 py-3 group">
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-0.5">🔑 Password</p>
                            <p class="font-mono font-bold text-gray-900 text-sm tracking-widest" id="copyPass">{{ $newPassword }}</p>
                        </div>
                        <button onclick="copyText('{{ $newPassword }}', this)" title="Salin password"
                                class="ml-3 text-gray-400 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[20px]">content_copy</span>
                        </button>
                    </div>
                </div>
                <p class="text-xs text-green-700 mt-3">
                    <span class="material-symbols-outlined text-[14px] align-middle">info</span>
                    Sampaikan info ini ke tutor. Password dapat diganti setelah login pertama.
                </p>
            </div>
            <button onclick="document.getElementById('accountInfoBox').remove()" class="text-gray-400 hover:text-gray-600">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
    </div>
@elseif(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-2">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        <span class="font-medium text-sm">{{ session('success') }}</span>
    </div>
@endif

<!-- Table Container Card -->
<div class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] overflow-hidden border border-gray-100">
    <!-- Filters & Search Inner Row -->
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <form action="{{ route('admin.tutors.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-[20px]">search</span>
                <input name="search" value="{{ request('search') }}" class="pl-10 pr-4 py-2 bg-gray-100 border-none rounded-lg text-body-sm focus:ring-2 focus:ring-primary focus:bg-white w-64 transition-all text-sm outline-none" placeholder="Cari NIP atau nama..." type="text">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-all">Filter</button>
        </form>
        <div>
            <p class="text-body-sm text-outline whitespace-nowrap">Menampilkan {{ $tutors->firstItem() ?? 0 }}-{{ $tutors->lastItem() ?? 0 }} dari {{ $tutors->total() }} Tutor</p>
        </div>
    </div>
    
    <!-- Actual Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">NIP</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Spesialisasi</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Pendidikan</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">No. HP</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Status</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tutors as $t)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4 text-body-sm font-semibold text-primary">{{ $t->nip ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-primary font-bold text-xs uppercase">
                                {{ strtoupper(substr($t->user->name, 0, 2)) }}
                            </div>
                            <span class="text-body-sm font-medium text-on-surface">{{ $t->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $t->specialization ?? '-' }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $t->education ?? '-' }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $t->user->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-[12px] font-bold rounded-full {{ $t->is_active ? 'bg-green-100 text-success' : 'bg-gray-200 text-gray-600' }}">
                            {{ $t->is_active ? 'Aktif' : 'Inaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.tutors.edit', $t->id) }}" class="p-2 text-gray-500 hover:text-primary hover:bg-primary-tint rounded-lg transition-all" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.tutors.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan tutor ini?')" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-500 hover:text-error hover:bg-error-container rounded-lg transition-all" title="Hapus / Nonaktifkan">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                        <p class="font-medium text-gray-600">Belum ada data tutor ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Footer -->
    <div class="p-6 border-t border-gray-100">
        {{ $tutors->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            const icon = btn.querySelector('.material-symbols-outlined');
            icon.textContent = 'check';
            btn.classList.add('text-green-500');
            setTimeout(() => {
                icon.textContent = 'content_copy';
                btn.classList.remove('text-green-500');
            }, 2000);
        });
    }
</script>
@endpush
