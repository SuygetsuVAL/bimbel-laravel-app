@extends('layouts.admin')
@section('title', 'Laporan Kinerja')

@section('content')
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Laporan & Ekspor</h2>
        <p class="text-body-md text-outline">Ikhtisar performa operasional, kehadiran, nilai, dan finansial AozoraLearn.</p>
    </div>
</div>

<!-- Bento Grid: Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Pendapatan -->
    <div class="bg-white p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] flex flex-col justify-between border-l-4 border-primary border border-gray-100">
        <div>
            <div class="flex justify-between items-start mb-2">
                <span class="text-label-md text-outline uppercase tracking-wider">Total Pendapatan (All-Time)</span>
                <div class="bg-primary-tint p-2 rounded-lg text-primary">
                    <span class="material-symbols-outlined">payments</span>
                </div>
            </div>
            <h3 class="text-headline-md font-headline-md text-on-surface font-bold text-2xl">Rp {{ number_format(\App\Models\Payment::paid()->sum('amount'), 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs">
            <span class="flex items-center text-success font-label-md font-bold">
                <span class="material-symbols-outlined text-sm">trending_up</span> Lunas
            </span>
            <span class="text-body-sm text-outline">Terverifikasi di sistem</span>
        </div>
    </div>
    
    <!-- Tingkat Kehadiran -->
    <div class="bg-white p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] flex flex-col justify-between border-l-4 border-success border border-gray-100">
        <div>
            <div class="flex justify-between items-start mb-2">
                <span class="text-label-md text-outline uppercase tracking-wider">Rata-rata Kehadiran Siswa</span>
                <div class="bg-green-50 p-2 rounded-lg text-success">
                    <span class="material-symbols-outlined">person_check</span>
                </div>
            </div>
            @php
                $totalAtt = \App\Models\Attendance::count();
                $presentAtt = \App\Models\Attendance::where('status', 'hadir')->count();
                $pctAtt = $totalAtt > 0 ? ($presentAtt / $totalAtt) * 100 : 0;
            @endphp
            <h3 class="text-headline-md font-headline-md text-on-surface font-bold text-2xl">{{ round($pctAtt, 1) }}%</h3>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs">
            <span class="text-body-sm text-outline">Dihitung dari seluruh sesi presensi</span>
        </div>
    </div>
    
    <!-- Kelas Aktif -->
    <div class="bg-white p-6 rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] flex flex-col justify-between border-l-4 border-secondary border border-gray-100">
        <div>
            <div class="flex justify-between items-start mb-2">
                <span class="text-label-md text-outline uppercase tracking-wider">Kelas Aktif</span>
                <div class="bg-secondary-fixed p-2 rounded-lg text-secondary">
                    <span class="material-symbols-outlined">school</span>
                </div>
            </div>
            <h3 class="text-headline-md font-headline-md text-on-surface font-bold text-2xl">{{ \App\Models\ClassRoom::where('is_active', true)->count() }} Kelas</h3>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs">
            <span class="text-body-sm text-outline">Bimbingan belajar aktif pekan ini</span>
        </div>
    </div>
</div>

<!-- Export Controls Layout -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Ekspor Kehadiran -->
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-6 space-y-4">
        <div class="flex items-center gap-3 border-b pb-3 mb-4">
            <span class="material-symbols-outlined text-primary text-2xl">analytics</span>
            <h3 class="font-bold text-gray-800 text-base">Ekspor Presensi Kehadiran</h3>
        </div>
        
        <form action="{{ route('admin.reports.attendance.export') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Pilih Kelas *</label>
                <select name="class_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->subject->name }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="date_start" required value="{{ now()->startOfMonth()->toDateString() }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-primary focus:border-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Tanggal Selesai *</label>
                    <input type="date" name="date_end" required value="{{ now()->toDateString() }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:ring-primary focus:border-primary outline-none transition-all">
                </div>
            </div>
            
            <button type="submit" class="w-full py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm font-semibold transition-all active:scale-95 flex items-center justify-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-sm">table_view</span>
                Unduh Excel Presensi
            </button>
        </form>
    </div>

    <!-- Ekspor Nilai -->
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-6 space-y-4">
        <div class="flex items-center gap-3 border-b pb-3 mb-4">
            <span class="material-symbols-outlined text-primary text-2xl">description</span>
            <h3 class="font-bold text-gray-800 text-base">Ekspor Nilai Siswa</h3>
        </div>
        
        <form action="{{ route('admin.reports.grades.export') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Pilih Kelas *</label>
                <select name="class_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->subject->name }})</option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="w-full py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm font-semibold transition-all active:scale-95 flex items-center justify-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-sm">table_view</span>
                Unduh Excel Nilai
            </button>
        </form>
    </div>

    <!-- Ekspor Keuangan -->
    <div class="bg-white rounded-xl shadow-sm border border-outline-variant p-6 space-y-4">
        <div class="flex items-center gap-3 border-b pb-3 mb-4">
            <span class="material-symbols-outlined text-primary text-2xl">payments</span>
            <h3 class="font-bold text-gray-800 text-base">Ekspor Laporan Keuangan</h3>
        </div>
        
        <form action="{{ route('admin.reports.payments.export') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-2">Pilih Periode Bulan *</label>
                <input type="month" name="period" required value="{{ now()->format('Y-m') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
            </div>
            
            <button type="submit" class="w-full py-2.5 bg-primary hover:bg-primary-dark text-white rounded-lg text-sm font-semibold transition-all active:scale-95 flex items-center justify-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-sm">table_view</span>
                Unduh Excel Keuangan
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Hover animation for bento cards
    document.querySelectorAll('.bg-white.p-6').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-2px)';
            card.style.transition = 'transform 0.2s ease';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
        });
    });
</script>
@endpush
@endsection
