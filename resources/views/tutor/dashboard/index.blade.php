@extends('layouts.tutor')
@section('title', 'Dashboard')

@section('content')
<!-- Header Section -->
<header class="flex flex-col md:flex-row justify-between items-start md:items-end mb-6 gap-4">
    <div>
        <p class="text-body-sm font-body-sm text-gray-500 mb-1">Ikhtisar Tutor</p>
        <h2 class="text-headline-lg font-headline-lg text-gray-900 tracking-tight font-bold text-2xl">SELAMAT DATANG, {{ strtoupper(auth()->user()->name) }}!</h2>
    </div>
</header>

<!-- Bento Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Schedule Column -->
    <div class="lg:col-span-2 flex flex-col gap-4">
        <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100">
            <h3 class="text-headline-sm font-headline-sm text-gray-900 font-semibold text-lg">Jadwal Kelas Hari Ini</h3>
            <span class="bg-primary-tint text-primary text-label-sm font-label-sm px-3 py-1 rounded-full font-bold">
                {{ $schedulesToday->count() }} Kelas
            </span>
        </div>
        
        <!-- Schedule Cards -->
        @forelse($schedulesToday as $sched)
            <div class="bg-white p-5 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between hover:border-primary-tint transition-colors group duration-300">
                <div class="flex items-center gap-4 w-full sm:w-auto">
                    <div class="bg-primary-tint text-primary p-3 rounded-lg flex flex-col items-center justify-center min-w-[90px] font-semibold text-sm">
                        <span class="font-bold">{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}</span>
                        <span class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}</span>
                    </div>
                    <div>
                        <h4 class="text-body-md font-bold text-gray-900 mb-1 group-hover:text-primary transition-colors">{{ $sched->classRoom->name }}</h4>
                        <p class="text-body-sm text-gray-500 flex items-center gap-1 text-xs">
                            <span class="material-symbols-outlined text-[16px]">subject</span> {{ $sched->classRoom->subject->name }} | {{ $sched->room }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-2 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
                    <a href="{{ route('tutor.attendance.create', $sched->id) }}" class="flex-shrink-0 bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">how_to_reg</span> Presensi
                    </a>
                    <a href="{{ route('tutor.materials.create') }}" class="flex-shrink-0 bg-white border border-gray-300 text-gray-700 px-3 py-2 rounded-lg text-xs font-semibold hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">upload_file</span> Upload Materi
                    </a>
                    <a href="{{ route('tutor.grades.create') }}" class="flex-shrink-0 bg-primary text-white px-3 py-2 rounded-lg text-xs font-semibold hover:bg-primary-dark transition-colors shadow-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">edit_note</span> Input Nilai
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 text-center text-gray-400">
                <span class="material-symbols-outlined text-4xl mb-2">calendar_today</span>
                <p class="font-medium text-gray-600">Tidak ada jadwal mengajar untuk hari ini.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Side Stats & Materials Column -->
    <div class="flex flex-col gap-6">
        <!-- Weekly Stats Grid -->
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-white p-5 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 col-span-2">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-label-md font-medium text-gray-500 text-sm">Rata-rata Kehadiran Pekan Ini</span>
                    <span class="material-symbols-outlined text-success text-[20px]">trending_up</span>
                </div>
                <div class="flex items-end gap-2">
                    <span class="text-headline-md font-bold text-gray-900 text-2xl">{{ $avgAttendance }}%</span>
                    <span class="text-label-sm text-success pb-1 text-xs">Pekan ini</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100">
                <div class="flex flex-col mb-1">
                    <span class="material-symbols-outlined text-primary mb-2 text-[20px]">groups</span>
                    <span class="text-headline-md font-bold text-gray-900 text-xl">{{ $totalStudents }}</span>
                </div>
                <span class="text-label-sm text-gray-500 text-xs">Siswa Anda</span>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100">
                <div class="flex flex-col mb-1">
                    <span class="material-symbols-outlined text-primary mb-2 text-[20px]">library_books</span>
                    <span class="text-headline-md font-bold text-gray-900 text-xl">{{ $recentMaterials->count() }}</span>
                </div>
                <span class="text-label-sm text-gray-500 text-xs">Materi Diunggah</span>
            </div>
        </div>
        
        <!-- Recent Materials Card -->
        <div class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 overflow-hidden flex flex-col">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="text-label-md font-bold text-gray-900 text-sm">Materi Pelajaran Terbaru</h3>
                <a class="text-primary text-label-sm hover:underline text-xs" href="{{ route('tutor.materials.index') }}">Lihat Semua</a>
            </div>
            <div class="p-3 flex flex-col gap-2">
                @forelse($recentMaterials as $mat)
                    <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                        <div class="w-10 h-10 rounded bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">{{ $mat->file_type == 'pdf' ? 'picture_as_pdf' : 'description' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-label-md font-bold text-gray-900 truncate group-hover:text-primary transition-colors text-sm">{{ $mat->title }}</p>
                            <p class="text-label-sm text-gray-500 text-xs">{{ $mat->classRoom->name }} • {{ strtoupper($mat->file_type) }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm text-center py-4">Belum ada materi pelajaran.</p>
                @endforelse
            </div>
            <div class="p-4 mt-auto border-t border-gray-100">
                <a href="{{ route('tutor.materials.create') }}" class="w-full bg-white border-2 border-primary text-primary px-4 py-2 rounded-lg text-xs font-bold hover:bg-primary hover:text-white transition-colors flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add</span> Unggah Materi Baru
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
