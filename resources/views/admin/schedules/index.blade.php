@extends('layouts.admin')
@section('title', 'Jadwal Kelas')

@section('content')
<!-- Page Header Actions -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h3 class="text-headline-md text-on-surface mb-1">Manajemen Jadwal</h3>
        <p class="text-body-sm text-outline">Kelola jadwal waktu belajar mengajar, ruangan, dan kelas bimbel.</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.schedules.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-label-md rounded-lg hover:bg-primary-dark transition-all shadow-md active:scale-95 text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">add</span>
            Tambah Jadwal
        </a>
    </div>
</div>

<!-- Table Container Card -->
<div class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] overflow-hidden border border-gray-100">
    <!-- Filters & Search Inner Row -->
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <form action="{{ route('admin.schedules.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="class_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-body-sm rounded-lg px-4 py-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm">
                <option value="">Semua Kelas</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <select name="day_of_week" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-body-sm rounded-lg px-4 py-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm">
                <option value="">Semua Hari</option>
                @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                    <option value="{{ $hari }}" {{ request('day_of_week') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-all">Filter</button>
        </form>
        <div>
            <p class="text-body-sm text-outline whitespace-nowrap">Menampilkan {{ $schedules->firstItem() ?? 0 }}-{{ $schedules->lastItem() ?? 0 }} dari {{ $schedules->total() }} Jadwal</p>
        </div>
    </div>
    
    <!-- Actual Data Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Kelas</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Hari</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Waktu Mulai</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Waktu Selesai</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold">Ruangan</th>
                    <th class="px-6 py-4 text-label-md text-gray-700 uppercase tracking-wider font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $s)
                <tr class="hover:bg-gray-50 transition-colors group">
                    <td class="px-6 py-4 font-semibold text-on-surface text-body-sm">{{ $s->classRoom->name }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant font-medium">{{ $s->day_of_week }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $s->start_time }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">{{ $s->end_time }}</td>
                    <td class="px-6 py-4 text-body-sm text-on-surface-variant font-semibold text-primary">{{ $s->room ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.schedules.edit', $s->id) }}" class="p-2 text-gray-500 hover:text-primary hover:bg-primary-tint rounded-lg transition-all" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.schedules.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan jadwal ini?')" class="inline-block">
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
                        <p class="font-medium text-gray-600">Belum ada data jadwal ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination Footer -->
    <div class="p-6 border-t border-gray-100">
        {{ $schedules->links() }}
    </div>
</div>
@endsection
