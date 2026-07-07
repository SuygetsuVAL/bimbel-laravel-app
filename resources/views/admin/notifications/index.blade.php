@extends('layouts.admin')
@section('title', 'Pengumuman')

@section('content')
<!-- Header Section -->
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h2 class="text-headline-md font-headline-md text-primary mb-1">Pengumuman & Notifikasi</h2>
        <p class="text-body-md text-on-surface-variant">Kirim dan kelola notifikasi broadcast ke seluruh civitas AozoraLearn.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    <!-- Form Box -->
    <div class="lg:col-span-4 bg-white rounded-xl shadow-sm border border-outline-variant p-6">
        <h3 class="font-bold text-gray-800 text-sm mb-4 uppercase tracking-wider text-primary border-b pb-2">Buat Pengumuman Baru</h3>
        
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.notifications.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pengumuman *</label>
                <input type="text" name="title" required placeholder="Contoh: Libur Hari Raya" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Isi Pesan Pengumuman *</label>
                <textarea name="message" required rows="4" placeholder="Tulis rincian pesan pengumuman..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Target Penerima *</label>
                <select name="target_role" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="all">Semua Pengguna</option>
                    <option value="tutor">Tutor Saja</option>
                    <option value="student">Siswa Saja</option>
                </select>
            </div>
            
            <button type="submit" class="w-full py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors shadow-md active:scale-95 flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[20px]">send</span>
                Kirim Broadcast
            </button>
        </form>
    </div>

    <!-- History List -->
    <div class="lg:col-span-8 bg-white rounded-xl shadow-sm border border-outline-variant p-6">
        <h3 class="font-bold text-gray-800 text-sm mb-4 uppercase tracking-wider text-primary border-b pb-2">Riwayat Pengumuman Terkirim</h3>
        
        @if($notifications->isEmpty())
            <div class="text-center py-12 text-gray-400">
                <span class="material-symbols-outlined text-4xl mb-2">campaign</span>
                <p class="font-medium text-gray-600">Belum ada riwayat pengumuman</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($notifications as $notif)
                    <div class="flex justify-between items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100/50 transition-colors border border-gray-100">
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-900 text-sm">{{ $notif->title }}</h4>
                                <span class="px-2.5 py-0.5 bg-primary-tint text-primary text-[10px] font-bold rounded-full uppercase">Target: {{ $notif->target_role }}</span>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $notif->message }}</p>
                            <div class="flex items-center gap-2 text-[10px] text-gray-400">
                                <span class="material-symbols-outlined text-xs">schedule</span>
                                <span>{{ $notif->created_at->diffForHumans() }}</span>
                                <span>•</span>
                                <span>Dikirim oleh: {{ $notif->sender->name ?? 'Admin' }}</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.notifications.destroy', $notif->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1 text-gray-400 hover:text-error hover:bg-red-50 rounded-full transition-all" title="Hapus Pengumuman">
                                <span class="material-symbols-outlined text-[20px]">delete</span>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
