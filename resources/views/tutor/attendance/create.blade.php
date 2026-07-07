@extends('layouts.tutor')
@section('title', 'Input Kehadiran')

@section('content')
<style>
    /* Custom radio styling for the attendance status */
    .status-radio:checked + label {
        border-color: currentColor;
        background-color: rgba(var(--color-rgb), 0.1);
    }
</style>

<div class="max-w-[1160px] mx-auto space-y-6">
    <!-- Context Header Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex justify-between items-center">
            <div>
                <h2 class="font-headline-md text-gray-900 font-bold text-xl">{{ $schedule->classRoom->name }}</h2>
                <div class="flex items-center gap-4 mt-2 text-gray-500 text-xs">
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                        <span>{{ now()->translatedFormat('d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                        <span>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB</span>
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('tutor.attendance.index') }}" class="px-4 py-2 border-2 border-primary text-primary font-semibold rounded-lg text-xs hover:bg-primary-tint transition-all active:scale-[0.98]">
                    Kembali
                </a>
            </div>
        </div>
        
        <!-- Bento Real-time Summary -->
        <div class="bg-primary-tint p-6 rounded-2xl border border-primary/10 shadow-sm">
            <h3 class="font-label-md text-primary mb-3 text-sm font-bold">Rekap Kehadiran Real-time</h3>
            <div class="grid grid-cols-4 gap-2">
                <div class="bg-white/60 p-2 rounded-lg flex flex-col items-center">
                    <span class="text-success font-bold text-lg" id="count-hadir">0</span>
                    <span class="text-[9px] text-gray-500 uppercase font-bold">Hadir</span>
                </div>
                <div class="bg-white/60 p-2 rounded-lg flex flex-col items-center">
                    <span class="text-amber-500 font-bold text-lg" id="count-sakit">0</span>
                    <span class="text-[9px] text-gray-500 uppercase font-bold">Sakit</span>
                </div>
                <div class="bg-white/60 p-2 rounded-lg flex flex-col items-center">
                    <span class="text-orange-500 font-bold text-lg" id="count-izin">0</span>
                    <span class="text-[9px] text-gray-500 uppercase font-bold">Izin</span>
                </div>
                <div class="bg-white/60 p-2 rounded-lg flex flex-col items-center">
                    <span class="text-error font-bold text-lg" id="count-alpha">0</span>
                    <span class="text-[9px] text-gray-500 uppercase font-bold">Alpha</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Kehadiran -->
    <form action="{{ route('tutor.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="date" value="{{ now()->toDateString() }}">

        <!-- Attendance Table Section -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="px-6 py-4 font-semibold text-gray-700 w-16 text-sm">No</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-sm">Siswa</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center text-sm w-20">Hadir</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center text-sm w-20">Sakit</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center text-sm w-20">Izin</th>
                            <th class="px-6 py-4 font-semibold text-gray-700 text-center text-sm w-20">Alpha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="attendance-list">
                        @forelse($students as $idx => $student)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-gray-500 text-sm">{{ sprintf('%02d', $idx + 1) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary-tint text-primary flex items-center justify-center font-bold text-xs">
                                        {{ strtoupper(substr($student->user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $student->user->name }}</p>
                                        <p class="text-[11px] text-gray-400">NIS: {{ $student->nis }}</p>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Hadir -->
                            <td class="px-6 py-4 text-center">
                                <input checked class="hidden status-radio" id="s-{{ $student->id }}-h" name="attendance[{{ $student->id }}]" onchange="updateSummary()" type="radio" value="hadir"/>
                                <label class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-gray-200 cursor-pointer text-success hover:border-success transition-all text-xs font-bold" for="s-{{ $student->id }}-h" style="--color-rgb: 16, 185, 129">H</label>
                            </td>
                            
                            <!-- Sakit -->
                            <td class="px-6 py-4 text-center">
                                <input class="hidden status-radio" id="s-{{ $student->id }}-s" name="attendance[{{ $student->id }}]" onchange="updateSummary()" type="radio" value="sakit"/>
                                <label class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-gray-200 cursor-pointer text-amber-500 hover:border-amber-500 transition-all text-xs font-bold" for="s-{{ $student->id }}-s" style="--color-rgb: 245, 158, 11">S</label>
                            </td>
                            
                            <!-- Izin -->
                            <td class="px-6 py-4 text-center">
                                <input class="hidden status-radio" id="s-{{ $student->id }}-i" name="attendance[{{ $student->id }}]" onchange="updateSummary()" type="radio" value="izin"/>
                                <label class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-gray-200 cursor-pointer text-orange-500 hover:border-orange-500 transition-all text-xs font-bold" for="s-{{ $student->id }}-i" style="--color-rgb: 234, 88, 12">I</label>
                            </td>
                            
                            <!-- Alpha -->
                            <td class="px-6 py-4 text-center">
                                <input class="hidden status-radio" id="s-{{ $student->id }}-a" name="attendance[{{ $student->id }}]" onchange="updateSummary()" type="radio" value="alpha"/>
                                <label class="inline-flex items-center justify-center w-8 h-8 rounded-full border-2 border-gray-200 cursor-pointer text-error hover:border-error transition-all text-xs font-bold" for="s-{{ $student->id }}-a" style="--color-rgb: 239, 68, 68">A</label>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <span class="material-symbols-outlined text-4xl mb-2">people</span>
                                <p class="font-medium text-gray-600">Tidak ada siswa terdaftar di kelas ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Footer Actions -->
            <div class="bg-gray-50 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-body-sm text-gray-500 text-xs">
                    <span class="font-bold">Info:</span> Klik pada inisial status (H/S/I/A) untuk mencatat kehadiran siswa secara cepat.
                </p>
                <div class="flex gap-3">
                    <a href="{{ route('tutor.attendance.index') }}" class="px-6 py-2 rounded-lg border-2 border-primary text-primary font-semibold text-xs hover:bg-primary-tint transition-all active:scale-95">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 rounded-lg bg-primary text-white font-semibold text-xs hover:bg-primary-dark transition-all shadow-md active:scale-95 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Kehadiran
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function updateSummary() {
        const statuses = {
            hadir: 0,
            sakit: 0,
            izin: 0,
            alpha: 0
        };

        document.querySelectorAll('.status-radio:checked').forEach(radio => {
            statuses[radio.value]++;
        });

        document.getElementById('count-hadir').innerText = statuses.hadir;
        document.getElementById('count-sakit').innerText = statuses.sakit;
        document.getElementById('count-izin').innerText = statuses.izin;
        document.getElementById('count-alpha').innerText = statuses.alpha;
    }

    // Initialize counts on load
    window.addEventListener('DOMContentLoaded', updateSummary);
</script>
@endsection
