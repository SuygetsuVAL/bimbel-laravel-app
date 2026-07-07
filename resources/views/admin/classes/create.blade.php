@extends('layouts.admin')
@section('title', 'Tambah Kelas')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h3 class="text-headline-md text-on-surface mb-1">Tambah Kelas</h3>
        <p class="text-body-sm text-outline">Tambahkan data rombel/kelas baru ke sistem AozoraLearn.</p>
    </div>
    <div>
        <a href="{{ route('admin.classes.index') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary font-label-md rounded-lg hover:bg-primary-tint transition-all text-sm font-semibold">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            Kembali
        </a>
    </div>
</div>

<div class="max-w-2xl bg-white rounded-xl p-8 border border-outline-variant shadow-[0_4px_12px_rgba(0,0,0,0.08)]">
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-start gap-3">
            <span class="material-symbols-outlined text-red-600 mt-0.5">error</span>
            <div>
                <p class="font-semibold text-sm">Gagal Menyimpan Data:</p>
                <p class="text-xs mt-1">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kelas *</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kelas Matematika X-A" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tingkat Kelas</label>
                <input type="text" name="level" value="{{ old('level') }}" placeholder="Contoh: X SMA, VIII SMP" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kapasitas Maksimal Siswa</label>
                <input type="number" name="max_students" value="{{ old('max_students', 20) }}" min="1" max="100" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran *</label>
                <select name="subject_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tutor Pengajar</label>
                <select name="tutor_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="">Pilih Tutor (Dapat dikosongkan dahulu)</option>
                    @foreach($tutors as $t)
                        <option value="{{ $t->id }}" {{ old('tutor_id') == $t->id ? 'selected' : '' }}>{{ $t->user->name }} ({{ $t->specialization ?? 'Umum' }})</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex gap-4 justify-end pt-6 border-t border-gray-100">
            <a href="{{ route('admin.classes.index') }}" class="px-6 py-2.5 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors shadow-md active:scale-95">Simpan Data</button>
        </div>
    </form>
</div>
@endsection
