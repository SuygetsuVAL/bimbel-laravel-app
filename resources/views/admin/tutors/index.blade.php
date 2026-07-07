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
