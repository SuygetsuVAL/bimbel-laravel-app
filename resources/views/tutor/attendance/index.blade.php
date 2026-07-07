@extends('layouts.tutor')
@section('title', 'Jadwal & Presensi')

@section('content')
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-headline-md font-headline-md text-primary-dark font-bold text-2xl">Jadwal Mengajar & Presensi</h2>
        <p class="text-body-md text-gray-500">Kelola kehadiran siswa di setiap sesi kelas bimbel Anda.</p>
    </div>
</div>

<!-- Dashboard Stats Summary (Bento Style) -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="glass-card p-6 rounded-xl shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start">
            <span class="material-symbols-outlined text-primary bg-primary-tint p-2 rounded-lg">school</span>
            <span class="text-success text-xs font-bold">{{ \App\Models\ClassRoom::where('tutor_id', auth()->user()->tutor->id)->where('is_active', true)->count() }} Aktif</span>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-bold">{{ \App\Models\ClassRoom::where('tutor_id', auth()->user()->tutor->id)->count() }} Kelas</h3>
            <p class="text-xs text-gray-500">Total Bimbingan</p>
        </div>
    </div>
    <div class="glass-card p-6 rounded-xl shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start">
            <span class="material-symbols-outlined text-primary bg-primary-tint p-2 rounded-lg">calendar_today</span>
            <span class="text-xs text-gray-500 font-semibold">Jadwal Sesi</span>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-bold">{{ \App\Models\Schedule::whereHas('classRoom', fn($q) => $q->where('tutor_id', auth()->user()->tutor->id))->count() }} Sesi</h3>
            <p class="text-xs text-gray-500">Terdaftar di Sistem</p>
        </div>
    </div>
    <div class="glass-card p-6 rounded-xl shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start">
            <span class="material-symbols-outlined text-primary bg-primary-tint p-2 rounded-lg">groups</span>
            <span class="text-success text-xs font-bold">Aktif</span>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-bold">
                {{ \App\Models\Student::whereIn('class_id', \App\Models\ClassRoom::where('tutor_id', auth()->user()->tutor->id)->pluck('id'))->count() }}
            </h3>
            <p class="text-xs text-gray-500">Siswa Bimbingan Anda</p>
        </div>
    </div>
    <div class="glass-card p-6 rounded-xl shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start">
            <span class="material-symbols-outlined text-amber-500 bg-orange-50 p-2 rounded-lg">star</span>
            <span class="text-gray-500 text-xs font-semibold">Rating</span>
        </div>
        <div class="mt-4">
            <h3 class="text-xl font-bold">4.9 / 5.0</h3>
            <p class="text-xs text-gray-500">Kepuasan Pengajaran</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: List of Schedules (Classes) -->
    <div class="lg:col-span-1 space-y-4">
        <h3 class="font-bold text-gray-900 text-base mb-2 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">pending_actions</span>
            Sesi Kelas Anda
        </h3>
        
        @php
            $schedules = \App\Models\Schedule::whereHas('classRoom', fn($q) => $q->where('tutor_id', auth()->user()->tutor->id))
                ->with(['classRoom.subject'])
                ->get()
                ->sortBy(function($item) {
                    $days = ['Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 7];
                    return $days[$item->day_of_week] ?? 9;
                });
        @endphp

        @forelse($schedules as $sched)
            <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-all relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-3 text-xs font-bold text-gray-400">
                    {{ $sched->day_of_week }}
                </div>
                <div class="space-y-3">
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm group-hover:text-primary transition-colors">{{ $sched->classRoom->name }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $sched->classRoom->subject->name }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="material-symbols-outlined text-sm">schedule</span>
                        <span>{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}</span>
                        <span>•</span>
                        <span>{{ $sched->room }}</span>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('tutor.attendance.create', $sched->id) }}" class="w-full text-center block px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg text-xs font-bold transition-all">
                            Input Presensi Sesi Ini
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 border border-gray-100 rounded-xl text-center text-gray-400">
                <span class="material-symbols-outlined text-3xl mb-1">calendar_today</span>
                <p class="text-sm font-semibold">Belum ada jadwal mengajar.</p>
            </div>
        @endforelse
    </div>

    <!-- Right: Attendance History Log -->
    <div class="lg:col-span-2 space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
            <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">fact_check</span>
                Riwayat Presensi Siswa
            </h3>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                <form action="{{ route('tutor.attendance.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <input type="date" name="date" value="{{ request('date') }}" class="px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-primary focus:border-primary outline-none transition-all">
                    <select name="class_id" class="px-3 py-1.5 border border-gray-300 rounded-lg text-xs focus:ring-primary focus:border-primary outline-none transition-all">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-4 py-1.5 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-primary-dark transition-all">Filter</button>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-5 py-3 text-xs font-bold text-gray-700">Tanggal</th>
                            <th class="px-5 py-3 text-xs font-bold text-gray-700">Siswa</th>
                            <th class="px-5 py-3 text-xs font-bold text-gray-700">Kelas</th>
                            <th class="px-5 py-3 text-xs font-bold text-gray-700 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($attendances as $a)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4 text-xs font-semibold text-gray-900">{{ $a->date->format('d M Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-primary-tint text-primary flex items-center justify-center font-bold text-[9px] uppercase">
                                        {{ strtoupper(substr($a->student->user->name, 0, 2)) }}
                                    </div>
                                    <span class="text-xs font-bold text-gray-900">{{ $a->student->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-600">{{ $a->schedule->classRoom->name }}</td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                    {{ $a->status == 'hadir' ? 'bg-green-100 text-success' : '' }}
                                    {{ $a->status == 'sakit' ? 'bg-yellow-100 text-amber-600' : '' }}
                                    {{ $a->status == 'izin' ? 'bg-orange-100 text-orange-600' : '' }}
                                    {{ $a->status == 'alpha' ? 'bg-red-100 text-error border border-red-200' : '' }}
                                ">
                                    {{ $a->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-gray-400">
                                <span class="material-symbols-outlined text-3xl mb-1">inbox</span>
                                <p class="text-xs font-medium text-gray-600">Belum ada riwayat presensi yang terekam.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($attendances->hasPages())
                <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
