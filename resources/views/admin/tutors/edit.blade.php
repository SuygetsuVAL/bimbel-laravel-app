@extends('layouts.admin')
@section('title', 'Edit Tutor')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h3 class="text-headline-md text-on-surface mb-1">Edit Data Tutor</h3>
        <p class="text-body-sm text-outline">Ubah profil dan informasi tutor pengajar AozoraLearn.</p>
    </div>
    <div>
        <a href="{{ route('admin.tutors.index') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary font-label-md rounded-lg hover:bg-primary-tint transition-all text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            Kembali
        </a>
    </div>
</div>

<div class="max-w-3xl bg-white rounded-xl p-8 border border-outline-variant shadow-[0_4px_12px_rgba(0,0,0,0.08)]">
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <div>
                <p class="font-semibold text-sm">Gagal Memperbarui Data:</p>
                <p class="text-xs mt-1">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.tutors.update', $tutor->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <h4 class="font-bold text-gray-800 text-sm border-b pb-2 mb-4 uppercase tracking-wider text-primary">Informasi Pribadi & Akun</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $tutor->user->name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIP (Nomor Induk Pegawai)</label>
                <input type="text" name="nip" value="{{ old('nip', $tutor->nip) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email', $tutor->user->email) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru (Kosongkan jika tidak diganti)</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
                <input type="text" name="phone" value="{{ old('phone', $tutor->user->phone) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Spesialisasi</label>
                <input type="text" name="specialization" value="{{ old('specialization', $tutor->specialization) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
                <input type="text" name="education" value="{{ old('education', $tutor->education) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="flex gap-4 justify-end pt-6 border-t border-gray-100">
            <a href="{{ route('admin.tutors.index') }}" class="px-6 py-2.5 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors shadow-md active:scale-95">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
