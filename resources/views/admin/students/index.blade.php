@extends('layouts.admin')
@section('title', 'Data Siswa')

@section('content')
<!-- Page Header Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h3 class="text-headline-md text-on-surface mb-1">Manajemen Siswa</h3>
        <p class="text-body-sm text-outline">Kelola data seluruh siswa AozoraLearn secara efisien.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary font-label-md rounded-lg hover:bg-primary-tint transition-all active:scale-95 text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">file_download</span>
            Export Laporan
        </a>
        <a href="{{ route('admin.students.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-label-md rounded-lg hover:bg-primary-dark transition-all shadow-md active:scale-95 text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Siswa
        </a>
    </div>
</div>

<!-- Dashboard Summary Widgets -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-label-sm text-outline uppercase tracking-wider mb-1">Total Siswa</p>
            <h4 class="text-headline-md font-bold">{{ $students->total() }}</h4>
            <span class="text-success text-label-sm flex items-center gap-1 mt-1 font-semibold">
                <span class="material-symbols-outlined text-[14px]">trending_up</span> Terdaftar di sistem
            </span>
        </div>
        <div class="w-12 h-12 rounded-full bg-primary-tint flex items-center justify-center text-primary">
            <span class="material-symbols-outlined text-[28px]">group</span>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-label-sm text-outline uppercase tracking-wider mb-1">Aktif Sekarang</p>
            <h4 class="text-headline-md font-bold">{{ \App\Models\Student::where('is_active', true)->count() }}</h4>
            <span class="text-outline text-label-sm mt-1 block">Status belajar aktif</span>
        </div>
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-success">
            <span class="material-symbols-outlined text-[28px]">person_check</span>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-label-sm text-outline uppercase tracking-wider mb-1">Pendaftaran Baru</p>
            <h4 class="text-headline-md font-bold">{{ \App\Models\Student::where('created_at', '>=', now()->startOfWeek())->count() }}</h4>
            <span class="text-secondary text-label-sm flex items-center gap-1 mt-1 font-semibold">
                Pekan ini
            </span>
        </div>
        <div class="w-12 h-12 rounded-full bg-secondary-fixed flex items-center justify-center text-secondary">
            <span class="material-symbols-outlined text-[28px]">person_add</span>
        </div>
    </div>
</div>

<!-- Table Container Card -->
<div class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] overflow-hidden border border-gray-100">
    <!-- Filters & Search Inner Row -->
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <form action="{{ route('admin.students.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="class_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-body-sm rounded-lg px-4 py-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm">
                <option value="">Semua Kelas</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->subject->name }})</option>
                @endforeach
            </select>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-[20px]">search</span>
                <input name="search" value="{{ request('search') }}" class="pl-10 pr-4 py-2 bg-gray-100 border-none rounded-lg text-body-sm focus:ring-2 focus:ring-primary focus:bg-white w-64 transition-all text-sm outline-none" placeholder="Cari Siswa..." type="text">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-all">Filter</button>
        </form>
        <div>
            <p class="text-body-sm text-outline whitespace-nowrap">Menampilkan {{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }} dari {{ $students->total() }} Siswa</p>
        </div>
    </div>
    
    <!-- Actual Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">NIS</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Nama Lengkap</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Kelas</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">No. HP</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Status</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $s)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4 text-body-sm font-semibold text-primary">{{ $s->nis }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-primary font-bold text-xs uppercase">
                                {{ strtoupper(substr($s->user->name, 0, 2)) }}
                            </div>
                            <span class="text-body-sm font-medium text-on-surface">{{ $s->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $s->classRoom->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $s->user->phone ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-[12px] font-bold rounded-full {{ $s->is_active ? 'bg-green-100 text-success' : 'bg-gray-200 text-gray-600' }}">
                            {{ $s->is_active ? 'Aktif' : 'Inaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.students.edit', $s->id) }}" class="p-2 text-gray-500 hover:text-primary hover:bg-primary-tint rounded-lg transition-all" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.students.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan siswa ini?')" class="inline-block">
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
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                        <p class="font-medium text-gray-600">Belum ada data siswa ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Footer -->
    <div class="p-6 border-t border-gray-100">
        {{ $students->links() }}
    </div>
</div>

<!-- Bento-style Analytics Grid (Bottom Section) -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="md:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex flex-col justify-between">
        <div>
            <h5 class="text-headline-sm text-on-surface mb-2 font-semibold">Trend Kehadiran</h5>
            <p class="text-body-sm text-outline">Rata-rata kehadiran seluruh siswa dalam sepekan terakhir.</p>
        </div>
        <div class="flex items-end gap-4 mt-6 h-32">
            <div class="flex-1 bg-primary-tint rounded-t-lg transition-all hover:bg-primary-light" style="height: 80%"></div>
            <div class="flex-1 bg-primary-tint rounded-t-lg transition-all hover:bg-primary-light" style="height: 85%"></div>
            <div class="flex-1 bg-primary-tint rounded-t-lg transition-all hover:bg-primary-light" style="height: 70%"></div>
            <div class="flex-1 bg-primary-tint rounded-t-lg transition-all hover:bg-primary-light" style="height: 95%"></div>
            <div class="flex-1 bg-primary rounded-t-lg transition-all hover:bg-primary-dark" style="height: 90%"></div>
            <div class="flex-1 bg-primary-tint rounded-t-lg transition-all hover:bg-primary-light" style="height: 75%"></div>
            <div class="flex-1 bg-primary-tint rounded-t-lg transition-all hover:bg-primary-light" style="height: 92%"></div>
        </div>
        <div class="flex justify-between mt-4 text-label-sm text-outline px-1 font-semibold text-xs">
            <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
        </div>
    </div>
    
    <div class="md:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
        <div class="flex-1">
            <h5 class="text-headline-sm text-on-surface mb-2 font-semibold">Siswa Berprestasi</h5>
            <p class="text-body-sm text-outline mb-4">Siswa dengan peningkatan nilai signifikan bulan ini.</p>
            <div class="flex -space-x-3">
                <div class="w-10 h-10 rounded-full border-2 border-white bg-blue-100 flex items-center justify-center font-bold text-xs text-blue-600">AW</div>
                <div class="w-10 h-10 rounded-full border-2 border-white bg-orange-100 flex items-center justify-center font-bold text-xs text-orange-600">BS</div>
                <div class="w-10 h-10 rounded-full border-2 border-white bg-green-100 flex items-center justify-center font-bold text-xs text-green-600">CL</div>
                <div class="w-10 h-10 rounded-full border-2 border-white bg-primary text-white flex items-center justify-center text-[12px] font-bold">+3</div>
            </div>
        </div>
        <div class="w-24 h-24 relative flex-shrink-0">
            <svg class="w-full h-full transform -rotate-90">
                <circle class="text-gray-100" cx="48" cy="48" fill="transparent" r="40" stroke="currentColor" stroke-width="8"></circle>
                <circle class="text-primary" cx="48" cy="48" fill="transparent" r="40" stroke="currentColor" stroke-dasharray="251.2" stroke-dashoffset="62.8" stroke-width="8"></circle>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-body-sm font-bold text-sm">75%</span>
            </div>
        </div>
    </div>
</div>
@endsection
