@extends('layouts.tutor')
@section('title', 'Rekap Nilai')

@section('content')
<div class="max-w-[1160px] mx-auto space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-headline-lg text-gray-900 font-bold text-2xl">Rekap Nilai Siswa</h2>
            <p class="text-gray-500 mt-1">Kelola hasil belajar siswa secara akurat dan efisien.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('tutor.grades.create') }}" class="flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-lg font-semibold text-xs hover:bg-primary-dark transition-all shadow-md active:scale-95">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Input Nilai Baru
            </a>
        </div>
    </div>

    @php
        $tutorClassIds = \App\Models\ClassRoom::where('tutor_id', auth()->user()->tutor->id)->pluck('id');
        $avgScore = round(\App\Models\Grade::whereIn('class_id', $tutorClassIds)->avg('score'), 1) ?? 0;
        $maxScore = \App\Models\Grade::whereIn('class_id', $tutorClassIds)->max('score') ?? 0;
        $totalInput = \App\Models\Grade::whereIn('class_id', $tutorClassIds)->count();
    @endphp

    <!-- Dashboard Stats / Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 border-l-4 border-l-primary">
            <p class="text-xs text-gray-500 uppercase font-semibold">Rata-rata Nilai</p>
            <div class="flex items-baseline gap-2 mt-1">
                <h3 class="text-2xl font-bold text-gray-900">{{ $avgScore }}</h3>
                <span class="text-xs text-gray-400">Poin</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-semibold">Total Nilai Diinput</p>
            <div class="flex items-baseline gap-2 mt-1">
                <h3 class="text-2xl font-bold text-gray-900">{{ $totalInput }}</h3>
                <span class="text-xs text-gray-400">Entry</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100">
            <p class="text-xs text-gray-500 uppercase font-semibold">Kategori Ujian</p>
            <div class="flex items-baseline gap-2 mt-1">
                <h3 class="text-2xl font-bold text-gray-900">5</h3>
                <span class="text-xs text-gray-400">Jenis</span>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-100 border-l-4 border-l-primary">
            <p class="text-xs text-gray-500 uppercase font-semibold">Nilai Tertinggi</p>
            <div class="flex items-baseline gap-2 mt-1">
                <h3 class="text-2xl font-bold text-primary">{{ $maxScore }}</h3>
                <span class="text-xs text-gray-400">Poin</span>
            </div>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Class Selection List (Left Column) -->
        <div class="lg:col-span-3 space-y-4">
            <h4 class="font-bold text-gray-800 text-sm px-1 uppercase tracking-wider text-primary">Daftar Kelas Anda</h4>
            <div class="space-y-3">
                @foreach($classes as $c)
                    @php
                        $isActive = request('class_id') == $c->id;
                    @endphp
                    <a href="{{ route('tutor.grades.index', array_merge(request()->query(), ['class_id' => $c->id])) }}" 
                       class="block p-4 rounded-xl transition-all border {{ $isActive ? 'bg-primary-tint border-primary shadow-sm' : 'bg-white border-gray-200 hover:border-primary-tint' }}">
                        <p class="font-bold text-sm {{ $isActive ? 'text-primary' : 'text-gray-900' }}">{{ $c->name }}</p>
                        <div class="flex justify-between items-center mt-2 text-xs text-gray-500">
                            <span>{{ $c->subject->name }}</span>
                            @if($isActive)
                                <span class="text-[10px] font-bold bg-primary text-white px-2 py-0.5 rounded-full">FILT</span>
                            @endif
                        </div>
                    </a>
                @endforeach
                @if(request('class_id'))
                    <a href="{{ route('tutor.grades.index', request()->except('class_id')) }}" class="block text-center py-2 text-xs font-bold text-primary hover:underline">
                        Bersihkan Filter Kelas
                    </a>
                @endif
            </div>
        </div>

        <!-- Main Data Table Container (Right Column) -->
        <div class="lg:col-span-9 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h4 class="font-bold text-gray-900 text-sm uppercase tracking-wider text-primary">Rekap Hasil Belajar</h4>
                    <p class="text-xs text-gray-500 mt-1">Daftar nilai ujian dan penugasan mandiri siswa.</p>
                </div>
                
                <form action="{{ route('tutor.grades.index') }}" method="GET" class="flex gap-2">
                    @if(request('class_id'))
                        <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                    @endif
                    <select name="exam_type" onchange="this.form.submit()" class="border-gray-300 rounded-lg text-xs py-1.5 focus:ring-primary focus:border-primary">
                        <option value="">Semua Ujian</option>
                        @foreach(['UH', 'UTS', 'UAS', 'Tugas', 'Quiz'] as $tipe)
                            <option value="{{ $tipe }}" {{ request('exam_type') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Nama Siswa</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Kelas</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Jenis Ujian</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center text-sm w-24">Skor / Maks</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center text-sm w-24">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($grades as $g)
                        <tr class="hover:bg-primary-tint/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-tint text-primary flex items-center justify-center font-bold text-xs uppercase">
                                        {{ strtoupper(substr($g->student->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $g->student->user->name }}</p>
                                        <p class="text-[11px] text-gray-400">NIS: {{ $g->student->nis }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600 font-medium">{{ $g->classRoom->name }}</td>
                            <td class="px-6 py-4 text-xs text-gray-500 font-bold uppercase">{{ $g->exam_type }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-sm text-primary">{{ $g->score }}</span>
                                <span class="text-xs text-gray-400">/ {{ $g->max_score }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $isLulus = $g->score_percent >= 70;
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $isLulus ? 'bg-green-100 text-success' : 'bg-red-100 text-error' }}">
                                    {{ $isLulus ? 'Lulus' : 'Remedial' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <span class="material-symbols-outlined text-4xl mb-2">grading</span>
                                <p class="font-medium text-gray-600">Belum ada data nilai diinput.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="p-6 border-t border-gray-100 bg-gray-50/50">
                {{ $grades->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
