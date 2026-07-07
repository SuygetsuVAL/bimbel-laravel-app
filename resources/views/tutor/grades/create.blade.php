@extends('layouts.tutor')
@section('title', 'Input Nilai')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="font-headline-lg text-gray-900 font-bold text-2xl">Input Nilai Ujian</h2>
            <p class="text-gray-500 mt-1">Masukkan hasil skor ujian/tugas mandiri siswa Anda.</p>
        </div>
        <a href="{{ route('tutor.grades.index') }}" class="flex items-center gap-2 px-4 py-2 border-2 border-primary text-primary font-semibold rounded-lg hover:bg-primary-tint transition-all text-xs">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl p-8 border border-gray-100 shadow-[0_4px_12px_rgba(0,0,0,0.06)]">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs flex items-center gap-2">
                <span class="material-symbols-outlined">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('tutor.grades.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kelas Bimbingan *</label>
                    <select name="class_id" id="class_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
                        <option value="">Pilih Kelas Anda</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ old('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->subject->name }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Siswa *</label>
                    <select name="student_id" id="student_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
                        <option value="">Pilih Siswa (Pilih Kelas Dulu)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Nilai / Kategori *</label>
                    <select name="exam_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
                        <option value="UH">UH (Ulangan Harian)</option>
                        <option value="UTS">UTS (Ujian Tengah Semester)</option>
                        <option value="UAS">UAS (Ujian Akhir Semester)</option>
                        <option value="Tugas">Tugas Mandiri</option>
                        <option value="Quiz">Quiz</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Skor / Nilai Didapat * (0 - 100)</label>
                    <input type="number" name="score" required min="0" max="100" placeholder="Contoh: 85" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan (Komentar Guru)</label>
                <input type="text" name="notes" placeholder="Contoh: Sangat baik dalam penyelesaian logika Trigonometri" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-primary focus:border-primary outline-none transition-all">
            </div>

            <div class="flex gap-4 justify-end pt-6 border-t border-gray-100">
                <a href="{{ route('tutor.grades.index') }}" class="px-6 py-2.5 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors shadow-md active:scale-95">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('class_id').addEventListener('change', function () {
        const classId = this.value;
        const studentSelect = document.getElementById('student_id');

        studentSelect.innerHTML = '<option value="">Memuat siswa...</option>';

        if (!classId) {
            studentSelect.innerHTML = '<option value="">Pilih Siswa (Pilih Kelas Dulu)</option>';
            return;
        }

        fetch(`{{ route('tutor.grades.students') }}?class_id=${classId}`)
            .then(res => res.json())
            .then(data => {
                studentSelect.innerHTML = '<option value="">Pilih Siswa</option>';
                data.forEach(student => {
                    studentSelect.innerHTML += `<option value="${student.id}">${student.name} (${student.nis})</option>`;
                });
            })
            .catch(err => {
                studentSelect.innerHTML = '<option value="">Gagal memuat siswa</option>';
                console.error(err);
            });
    });
</script>
@endpush
