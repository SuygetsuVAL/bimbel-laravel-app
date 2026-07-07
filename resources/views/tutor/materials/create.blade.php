@extends('layouts.tutor')
@section('title', 'Upload Materi')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-headline-lg text-gray-900 font-bold text-2xl">Upload Materi Baru</h2>
            <p class="text-gray-500 mt-1">Tambahkan berkas pendukung pembelajaran Anda ke kelas bimbel.</p>
        </div>
        <a href="{{ route('tutor.materials.index') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary-tint transition-all text-xs">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
    </div>

    <!-- Upload Card -->
    <div class="bg-white rounded-xl p-8 border border-gray-100 shadow-[0_4px_12px_rgba(0,0,0,0.06)]">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('tutor.materials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Kelas Sasaran *</label>
                <select name="class_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
                    <option value="">Pilih Kelas Anda</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->subject->name }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Materi Pelajaran *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Bab 5 Trigonometri & Persamaan Kuadrat" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi / Panduan Pengerjaan</label>
                <textarea name="description" rows="4" placeholder="Tulis instruksi pengerjaan tugas atau rangkuman singkat materi..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Unggah File (PDF, DOCX, XLSX, MP4, Maks. 50MB) *</label>
                <input type="file" name="file" required class="w-full px-4 py-3 border border-gray-300 border-dashed rounded-lg text-sm file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-primary-tint file:text-primary hover:file:bg-primary-tint cursor-pointer">
            </div>

            <div class="flex gap-4 justify-end pt-6 border-t border-gray-100">
                <a href="{{ route('tutor.materials.index') }}" class="px-6 py-2.5 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors shadow-md active:scale-95">Unggah Sekarang</button>
            </div>
        </form>
    </div>
</div>
@endsection
