@extends('layouts.admin')
@section('title', 'Keuangan')

@section('content')
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h2 class="text-headline-md font-headline-md text-primary mb-1">Manajemen Keuangan</h2>
        <p class="text-body-md text-on-surface-variant">Kelola pemasukan, tagihan, dan verifikasi pembayaran siswa.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 px-5 py-2.5 border-2 border-primary text-primary font-label-md rounded-lg hover:bg-primary-tint transition-all active:scale-[0.98] text-sm font-semibold">
            <span class="material-symbols-outlined">download</span>
            Ekspor Laporan
        </a>
        <button onclick="toggleModal('modal-invoice')" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-label-md rounded-lg shadow-md hover:bg-primary-dark transition-all active:scale-[0.98] text-sm font-semibold">
            <span class="material-symbols-outlined">add</span>
            Buat Tagihan Baru
        </button>
    </div>
</div>

<!-- Bento Grid Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Pemasukan -->
    <div class="glass-card p-6 rounded-xl shadow-sm border-l-4 border-success flex flex-col justify-between relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'FILL' 1;">payments</span>
        </div>
        <div>
            <p class="text-label-md text-gray-500 font-medium">Total Pemasukan ({{ $currentPeriod }})</p>
            <h3 class="text-headline-lg font-bold text-gray-900 mt-1">Rp {{ number_format($summary['total_lunas'], 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <span class="flex items-center text-success bg-success/10 px-2 py-0.5 rounded-full text-label-sm font-bold text-xs">
                <span class="material-symbols-outlined text-sm">trending_up</span> Lunas
            </span>
            <span class="text-label-sm text-gray-500 text-xs">Bulan ini</span>
        </div>
    </div>
    
    <!-- Tagihan Belum Dibayar -->
    <div class="glass-card p-6 rounded-xl shadow-sm border-l-4 border-error flex flex-col justify-between relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
        </div>
        <div>
            <p class="text-label-md text-gray-500 font-medium">Belum Dibayar ({{ $currentPeriod }})</p>
            <h3 class="text-headline-lg font-bold text-gray-900 mt-1">{{ $summary['total_belum_lunas'] }} Siswa</h3>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs">
            <span class="text-label-sm text-error font-bold">Menunggu Pembayaran</span>
        </div>
    </div>
    
    <!-- Proyeksi Pendapatan -->
    <div class="glass-card p-6 rounded-xl shadow-sm border-l-4 border-warning flex flex-col justify-between relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'FILL' 1;">query_stats</span>
        </div>
        <div>
            <p class="text-label-md text-gray-500 font-medium">Total Tagihan Dibuat</p>
            <h3 class="text-headline-lg font-bold text-gray-900 mt-1">Rp {{ number_format(\App\Models\Payment::byPeriod($currentPeriod)->sum('amount'), 0, ',', '.') }}</h3>
        </div>
        <div class="mt-4">
            <div class="w-full bg-gray-200 h-1.5 rounded-full overflow-hidden">
                @php
                    $totalInvoice = \App\Models\Payment::byPeriod($currentPeriod)->sum('amount');
                    $pct = $totalInvoice > 0 ? ($summary['total_lunas'] / $totalInvoice) * 100 : 0;
                @endphp
                <div class="bg-warning h-full rounded-full" style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-label-sm text-gray-500 mt-2 text-xs">Lunas tercapai {{ round($pct, 1) }}%</p>
        </div>
    </div>
</div>

<!-- Transaction Section -->
<div class="bg-white rounded-xl shadow-sm overflow-hidden border border-outline-variant">
    <!-- Table Controls -->
    <div class="p-6 flex flex-wrap justify-between items-center gap-4 border-b border-outline-variant">
        <form action="{{ route('admin.payments.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-[20px]">search</span>
                <input name="search" value="{{ request('search') }}" class="pl-10 pr-4 py-2 bg-gray-100 border-none rounded-lg text-body-sm focus:ring-2 focus:ring-primary focus:bg-white w-64 transition-all text-sm outline-none" placeholder="Cari siswa..." type="text">
            </div>
            <input type="month" name="period" value="{{ $currentPeriod }}" class="bg-gray-50 border border-gray-300 text-body-sm rounded-lg px-4 py-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm">
            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-body-sm rounded-lg px-4 py-2 focus:ring-primary focus:border-primary outline-none transition-all text-sm">
                <option value="">Semua Status</option>
                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-all">Filter</button>
        </form>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-label-md text-gray-700 border-b border-outline-variant">
                    <th class="px-6 py-4 font-semibold">Siswa</th>
                    <th class="px-6 py-4 font-semibold">NIS</th>
                    <th class="px-6 py-4 font-semibold">Kelas</th>
                    <th class="px-6 py-4 font-semibold">Periode</th>
                    <th class="px-6 py-4 font-semibold">Jumlah</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 text-right">Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($payments as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-tint text-primary flex items-center justify-center font-bold text-[10px] uppercase">
                                {{ strtoupper(substr($p->student->user->name, 0, 2)) }}
                            </div>
                            <span class="text-body-sm font-bold">{{ $p->student->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-body-sm text-gray-600">{{ $p->student->nis }}</td>
                    <td class="px-6 py-4 text-body-sm text-gray-600">{{ $p->student->classRoom->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-body-sm text-gray-600">{{ $p->period_formatted }}</td>
                    <td class="px-6 py-4 text-body-sm font-bold text-gray-900">{{ $p->amount_formatted }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold {{ $p->status == 'lunas' ? 'bg-green-100 text-success' : 'bg-red-100 text-error border border-red-200' }}">
                            <span class="material-symbols-outlined text-[16px]">{{ $p->status == 'lunas' ? 'check_circle' : 'schedule' }}</span>
                            {{ $p->status == 'lunas' ? 'Lunas' : 'Belum Bayar' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        @if($p->status != 'lunas')
                            <form action="{{ route('admin.payments.verify', $p->id) }}" method="POST" class="flex gap-2 items-center justify-end">
                                @csrf
                                <select name="method" required class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs outline-none focus:border-primary">
                                    <option value="tunai">Tunai</option>
                                    <option value="bca">BCA</option>
                                    <option value="mandiri">Mandiri</option>
                                    <option value="gopay">GoPay</option>
                                    <option value="ovo">OVO</option>
                                </select>
                                <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold transition-all">Verifikasi</button>
                            </form>
                        @else
                            <span class="text-xs text-gray-500 font-medium">Oleh: {{ $p->verifiedBy->name ?? 'Sistem' }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                        <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                        <p class="font-medium text-gray-600">Belum ada transaksi pembayaran</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-6 border-t border-outline-variant">
        {{ $payments->links() }}
    </div>
</div>

<!-- Modal Buat Tagihan -->
<div id="modal-invoice" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl border border-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-800 text-lg">Buat Tagihan Baru</h3>
            <button onclick="toggleModal('modal-invoice')" class="p-1 hover:bg-gray-100 rounded-full transition-colors">
                <span class="material-symbols-outlined text-gray-500">close</span>
            </button>
        </div>
        <form action="{{ route('admin.payments.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Siswa *</label>
                <select name="student_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">Pilih Siswa</option>
                    @foreach(\App\Models\Student::with('user')->where('is_active', true)->get() as $s)
                        <option value="{{ $s->id }}">{{ $s->user->name }} ({{ $s->nis }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Tagihan *</label>
                <input type="month" name="period" required value="{{ now()->format('Y-m') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Nominal (Rp) *</label>
                <input type="number" name="amount" required value="300000" min="1000" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="toggleModal('modal-invoice')" class="px-4 py-2.5 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors">Simpan Tagihan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection
