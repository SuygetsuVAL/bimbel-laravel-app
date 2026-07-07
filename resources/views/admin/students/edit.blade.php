@extends('layouts.admin')
@section('title', 'Edit Siswa')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h3 class="text-headline-md text-on-surface mb-1">Edit Data Siswa</h3>
        <p class="text-body-sm text-outline">Ubah data profil siswa AozoraLearn.</p>
    </div>
    <div>
        <a href="{{ route('admin.students.index') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary font-label-md rounded-lg hover:bg-primary-tint transition-all text-sm font-semibold">
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

    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <h4 class="font-bold text-gray-800 text-sm border-b pb-2 mb-4 uppercase tracking-wider text-primary">Informasi Pribadi & Akun</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" value="{{ old('name', $student->user->name) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Induk Siswa (NIS) *</label>
                <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Email *</label>
                <input type="email" name="email" value="{{ old('email', $student->user->email) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kata Sandi Baru (Kosongkan jika tidak diganti)</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor HP</label>
                <input type="text" name="phone" value="{{ old('phone', $student->user->phone) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas & Mata Pelajaran *</label>
                <select name="class_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">Pilih Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('class_id', $student->class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->subject->name }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $student->birth_date?->toDateString()) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bergabung</label>
                <input type="date" name="join_date" value="{{ old('join_date', $student->join_date?->toDateString()) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Rumah</label>
            <textarea name="address" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">{{ old('address', $student->address) }}</textarea>
        </div>

        <h4 class="font-bold text-gray-800 text-sm border-b pb-2 pt-4 mb-4 uppercase tracking-wider text-primary">Kontak Orang Tua / Wali</h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Orang Tua / Wali</label>
                <input type="text" name="parent_name" value="{{ old('parent_name', $student->parent_name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor HP Orang Tua / Wali</label>
                <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email Orang Tua / Wali</label>
            <input type="email" name="parent_email" value="{{ old('parent_email', $student->parent_email) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
        </div>

        <div class="flex gap-4 justify-end pt-6 border-t border-gray-100">
            <a href="{{ route('admin.students.index') }}" class="px-6 py-2.5 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors shadow-md active:scale-95">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
