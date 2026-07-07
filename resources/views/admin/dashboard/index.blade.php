@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
    <div>
        <h2 class="font-headline-lg text-headline-lg text-primary uppercase tracking-tight">SELAMAT DATANG, ADMIN!</h2>
        <p class="text-body-md text-outline">Berikut adalah rekap aktivitas dan data AozoraLearn hari ini.</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.students.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-label-md rounded-lg hover:bg-primary-dark transition-all shadow-md active:scale-95 text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            Tambah Siswa
        </a>
        <a href="{{ route('admin.tutors.create') }}" class="flex items-center gap-2 px-6 py-2.5 bg-white border-2 border-primary text-primary font-label-md rounded-lg hover:bg-primary-tint transition-all active:scale-95 text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">group_add</span>
            Tambah Tutor
        </a>
        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 px-6 py-2.5 bg-surface-container-high text-on-surface-variant font-label-md rounded-lg hover:bg-surface-variant transition-all active:scale-95 text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">file_download</span>
            Laporan
        </a>
    </div>
</div>

<!-- Bento Grid Summary -->
<div class="bento-grid mb-8">
    <!-- Main Stats -->
    <div class="col-span-12 md:col-span-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Students Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-outline-variant hover:shadow-md transition-all group duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-primary-tint rounded-lg text-primary">
                    <span class="material-symbols-outlined">group</span>
                </div>
                <span class="text-success text-label-sm font-bold bg-success/10 px-2 py-0.5 rounded-full">Aktif</span>
            </div>
            <h3 class="text-headline-md font-bold text-on-surface">{{ $totalStudents }}</h3>
            <p class="text-label-md text-outline">Siswa Aktif</p>
        </div>
        
        <!-- Tutors Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-outline-variant hover:shadow-md transition-all group duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-secondary-fixed rounded-lg text-secondary">
                    <span class="material-symbols-outlined">school</span>
                </div>
                <span class="text-label-sm font-medium text-outline">Tersertifikasi</span>
            </div>
            <h3 class="text-headline-md font-bold text-on-surface">{{ $totalTutors }}</h3>
            <p class="text-label-md text-outline">Tutor Aktif</p>
        </div>
        
        <!-- Classes Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-outline-variant hover:shadow-md transition-all group duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 bg-tertiary-fixed rounded-lg text-tertiary">
                    <span class="material-symbols-outlined">class</span>
                </div>
                <span class="text-warning text-label-sm font-bold bg-warning/10 px-2 py-0.5 rounded-full">Hari Ini</span>
            </div>
            <h3 class="text-headline-md font-bold text-on-surface">{{ $classesToday }}</h3>
            <p class="text-label-md text-outline">Kelas Terjadwal</p>
        </div>
    </div>
    
    <!-- Financial Card -->
    <div class="col-span-12 md:col-span-4 bg-primary text-white p-6 rounded-xl shadow-lg relative overflow-hidden flex flex-col justify-between">
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-6">
                <p class="text-label-md opacity-80">Pemasukan Bulan Ini</p>
                <span class="material-symbols-outlined opacity-80">account_balance_wallet</span>
            </div>
            <h3 class="text-headline-lg font-bold">Rp {{ number_format($newPayments, 0, ',', '.') }}</h3>
            <p class="text-label-sm bg-white/20 inline-block px-3 py-1 rounded-full mt-2">Status Pembayaran Lunas</p>
        </div>
        <div class="relative z-10 pt-4 border-t border-white/20 mt-4 flex items-center justify-between">
            <div>
                <p class="text-headline-sm font-bold text-error-container">Info Keuangan</p>
                <p class="text-label-sm opacity-80">Lihat rincian invoice siswa</p>
            </div>
            <a href="{{ route('admin.payments.index') }}" class="bg-white text-primary px-4 py-2 rounded-lg text-label-sm font-bold hover:bg-primary-tint transition-colors text-xs font-semibold">
                Kelola
            </a>
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="col-span-12 md:col-span-7 bg-white rounded-xl shadow-sm border border-outline-variant flex flex-col">
        <div class="p-6 border-b border-outline-variant flex justify-between items-center">
            <h4 class="font-headline-sm text-headline-sm text-on-surface">Aktivitas & Pengumuman</h4>
            <a class="text-primary text-label-md font-bold hover:underline" href="{{ route('admin.notifications.index') }}">Lihat Semua</a>
        </div>
        <div class="p-4 flex-1">
            @if($recentActivities->isEmpty())
                <p class="text-gray-500 text-sm text-center py-6">Belum ada pengumuman dikirim.</p>
            @else
                <div class="space-y-4">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start gap-4 p-3 hover:bg-gray-50 rounded-lg transition-colors group">
                        <div class="w-10 h-10 rounded-full bg-primary-tint flex items-center justify-center text-primary flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">campaign</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-body-md text-on-surface font-semibold">{{ $activity->title }}</p>
                            <p class="text-body-sm text-outline">{{ $activity->message }}</p>
                            <p class="text-label-sm text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    
    <!-- Schedule / Calendar Mini & Reminders -->
    <div class="col-span-12 md:col-span-5 bg-surface-container-low rounded-xl border border-outline-variant p-6 flex flex-col justify-between">
        <div>
            <div class="flex justify-between items-center mb-6">
                <h4 class="font-headline-sm text-headline-sm text-on-surface">Pemberitahuan Penting</h4>
                <span class="material-symbols-outlined text-outline">warning</span>
            </div>
            
            @if(empty($reminders))
                <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    ✅ Semua sistem berjalan lancar. Tidak ada peringatan baru.
                </div>
            @else
                <div class="space-y-3">
                    @foreach($reminders as $reminder)
                        <div class="bg-white p-4 rounded-lg shadow-sm border-l-4 border-warning flex items-start gap-3">
                            <span class="material-symbols-outlined text-warning">info</span>
                            <p class="text-label-md font-medium text-gray-800">{{ $reminder }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        <div class="mt-6">
            <h4 class="font-semibold text-gray-700 text-sm mb-3">Grafik Keuangan Pekan Ini</h4>
            <div class="bg-white p-4 rounded-lg border border-outline-variant">
                <canvas id="paymentChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Footer Stats Area -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    <div class="bg-surface-container-high p-6 rounded-xl flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm">
            <span class="material-symbols-outlined text-primary">trending_up</span>
        </div>
        <div>
            <p class="text-headline-sm font-bold text-on-surface">98%</p>
            <p class="text-label-sm text-outline">Tingkat Kehadiran</p>
        </div>
    </div>
    <div class="bg-surface-container-high p-6 rounded-xl flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm">
            <span class="material-symbols-outlined text-secondary">star</span>
        </div>
        <div>
            <p class="text-headline-sm font-bold text-on-surface">4.8</p>
            <p class="text-label-sm text-outline">Rating Rata-rata Tutor</p>
        </div>
    </div>
    <div class="bg-surface-container-high p-6 rounded-xl flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm">
            <span class="material-symbols-outlined text-success">task_alt</span>
        </div>
        <div>
            <p class="text-headline-sm font-bold text-on-surface">24</p>
            <p class="text-label-sm text-outline">Tugas Menunggu</p>
        </div>
    </div>
    <div class="bg-surface-container-high p-6 rounded-xl flex items-center gap-4">
        <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm">
            <span class="material-symbols-outlined text-tertiary">campaign</span>
        </div>
        <div>
            <p class="text-headline-sm font-bold text-on-surface">2</p>
            <p class="text-label-sm text-outline">Info Program Baru</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
new Chart(document.getElementById('paymentChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Pembayaran (Rp)',
            data: {!! json_encode($chartData) !!},
            backgroundColor: '#004ac6',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { 
            y: { 
                beginAtZero: true,
                grid: { display: false }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Simple card hover interaction
document.querySelectorAll('.bg-white.p-6').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-4px)';
    });
    card.style.transition = 'transform 0.3s ease';
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0px)';
    });
});
</script>
@endpush
