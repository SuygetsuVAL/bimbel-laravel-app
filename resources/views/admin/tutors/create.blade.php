@extends('layouts.admin')
@section('title', 'Tambah Tutor')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h3 class="text-headline-md text-on-surface mb-1">Tambah Tutor</h3>
        <p class="text-body-sm text-outline">Tambahkan data tutor baru ke sistem pembelajaran AozoraLearn.</p>
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
                <p class="font-semibold text-sm">Gagal Menyimpan Data:</p>
                <p class="text-xs mt-1">{{ $errors->first() }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.tutors.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <h4 class="font-bold text-gray-800 text-sm border-b pb-2 mb-4 uppercase tracking-wider text-primary">Informasi Pribadi</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" id="tutorName" value="{{ old('name') }}" required
                       placeholder="Contoh: Buk Desi"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                <p class="text-xs text-outline mt-1">Gelar seperti Pak/Buk/Bu/Dr akan diabaikan otomatis</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NIP (Nomor Induk Pegawai)</label>
                <input type="text" name="nip" value="{{ old('nip') }}" placeholder="Contoh: NIP001"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        {{-- Preview Akun Login Otomatis --}}
        <div id="accountPreview" class="hidden">
            <div class="p-5 rounded-xl border-2 border-primary/30 bg-gradient-to-br from-primary-tint to-blue-50 relative overflow-hidden">
                {{-- Dekorasi --}}
                <div class="absolute -top-4 -right-4 w-24 h-24 bg-primary/5 rounded-full"></div>
                <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-primary/5 rounded-full"></div>

                <div class="flex items-center gap-2 mb-4 relative">
                    <span class="material-symbols-outlined text-primary text-[22px]" style="font-variation-settings:'FILL' 1;">auto_awesome</span>
                    <p class="text-sm font-bold text-primary">Akun Login Akan Dibuat Otomatis</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 relative">
                    <div class="bg-white/80 backdrop-blur rounded-lg p-4 border border-primary/20">
                        <p class="text-xs font-medium text-outline uppercase tracking-wider mb-1.5">📧 Email Login</p>
                        <p id="previewEmail" class="text-sm font-bold text-on-surface font-mono">-</p>
                    </div>
                    <div class="bg-white/80 backdrop-blur rounded-lg p-4 border border-primary/20">
                        <p class="text-xs font-medium text-outline uppercase tracking-wider mb-1.5">🔑 Password Default</p>
                        <p class="text-sm font-bold text-on-surface font-mono tracking-widest">tutor123</p>
                    </div>
                </div>

                <p class="text-xs text-outline mt-3 relative">
                    <span class="material-symbols-outlined text-[14px] align-middle">info</span>
                    Tutor dapat mengganti password setelah login pertama kali.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon (HP)</label>
                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 0856XXXXXXXX"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Spesialisasi / Keahlian Mengajar</label>
                <input type="text" name="specialization" value="{{ old('specialization') }}" placeholder="Contoh: Matematika SMA, TOEFL Prep"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan Terakhir</label>
                <input type="text" name="education" value="{{ old('education') }}" placeholder="Contoh: S1 Pendidikan Fisika"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai Bergabung</label>
                <input type="date" name="join_date" value="{{ old('join_date', now()->toDateString()) }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
            </div>
        </div>

        <div class="flex gap-4 justify-end pt-6 border-t border-gray-100">
            <a href="{{ route('admin.tutors.index') }}" class="px-6 py-2.5 border rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Batal</a>
            <button type="submit" class="px-8 py-2.5 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors shadow-md active:scale-95">
                <span class="material-symbols-outlined text-[18px] align-middle mr-1">person_add</span>
                Simpan & Buat Akun
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const nameInput    = document.getElementById('tutorName');
    const preview      = document.getElementById('accountPreview');
    const previewEmail = document.getElementById('previewEmail');

    // Daftar gelar yang diabaikan (sama dengan server)
    const prefixes = ['pak', 'buk', 'bu', 'mr', 'mrs', 'ms', 'dr', 'prof'];

    function generateEmail(fullName) {
        const parts     = fullName.trim().split(/\s+/);
        let firstName   = parts[0].toLowerCase();

        // Skip gelar, ambil kata berikutnya
        if (prefixes.includes(firstName) && parts.length > 1) {
            firstName = parts[1].toLowerCase();
        }

        // Bersihkan karakter non-alfanumerik
        firstName = firstName.replace(/[^a-z0-9]/g, '');

        return firstName ? `${firstName}@aozoralearn.id` : null;
    }

    nameInput.addEventListener('input', function () {
        const email = generateEmail(this.value);

        if (email) {
            previewEmail.textContent = email;
            preview.classList.remove('hidden');

            // Animasi fade-in smooth
            preview.style.opacity = '0';
            preview.style.transform = 'translateY(8px)';
            preview.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            requestAnimationFrame(() => {
                preview.style.opacity = '1';
                preview.style.transform = 'translateY(0)';
            });
        } else {
            preview.classList.add('hidden');
        }
    });

    // Jika ada old value saat error validasi
    if (nameInput.value) nameInput.dispatchEvent(new Event('input'));
</script>
@endpush
