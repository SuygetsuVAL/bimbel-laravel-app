@extends('layouts.admin')
@section('title', 'Data Kelas')

@section('content')
<!-- Page Header Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h3 class="text-headline-md text-on-surface mb-1">Manajemen Kelas</h3>
        <p class="text-body-sm text-outline">Kelola data rombel, mata pelajaran, dan tutor kelas.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.classes.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-label-md rounded-lg hover:bg-primary-dark transition-all shadow-md active:scale-95 text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Kelas
        </a>
    </div>
</div>

<!-- Table Container Card -->
<div class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] overflow-hidden border border-gray-100">
    <!-- Filters & Search Inner Row -->
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <form action="{{ route('admin.classes.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-[20px]">search</span>
                <input name="search" value="{{ request('search') }}" class="pl-10 pr-4 py-2 bg-gray-100 border-none rounded-lg text-body-sm focus:ring-2 focus:ring-primary focus:bg-white w-64 transition-all text-sm outline-none" placeholder="Cari kelas..." type="text">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-all">Filter</button>
        </form>
        <div>
            <p class="text-body-sm text-outline whitespace-nowrap">Menampilkan {{ $classes->firstItem() ?? 0 }}-{{ $classes->lastItem() ?? 0 }} dari {{ $classes->total() }} Kelas</p>
        </div>
    </div>
    
    <!-- Actual Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Nama Kelas</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Tingkat</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Mata Pelajaran</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Tutor Pengajar</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Jumlah Siswa</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Status</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($classes as $c)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4 font-semibold text-on-surface text-body-sm">{{ $c->name }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $c->level ?? '-' }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant font-medium">{{ $c->subject->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">
                        @if($c->tutor)
                            {{ $c->tutor->user->name }}
                        @else
                            <span class="text-red-500 font-semibold italic text-xs">Belum ada Tutor</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $c->student_count }} / {{ $c->max_students }} Siswa</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-[12px] font-bold rounded-full {{ $c->is_active ? 'bg-green-100 text-success' : 'bg-gray-200 text-gray-600' }}">
                            {{ $c->is_active ? 'Aktif' : 'Inaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.classes.edit', $c->id) }}" class="p-2 text-gray-500 hover:text-primary hover:bg-primary-tint rounded-lg transition-all" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.classes.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan kelas ini?')" class="inline-block">
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
                        <p class="font-medium text-gray-600">Belum ada data kelas ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Footer -->
    <div class="p-6 border-t border-gray-100">
        {{ $classes->links() }}
    </div>
</div>
@endsection
