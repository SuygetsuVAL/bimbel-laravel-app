<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Material;
use App\Models\AppNotification;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user  = auth()->user();
        $tutor = $user->tutor;

        if (!$tutor) {
            return view('tutor.dashboard.index', [
                'schedulesToday'   => collect(),
                'totalStudents'    => 0,
                'avgAttendance'    => 0,
                'recentMaterials'  => collect(),
                'notifications'    => collect(),
            ]);
        }

        // ── Jadwal Hari Ini milik tutor ini ──────────────────────────────────
        $schedulesToday = Schedule::today()
            ->whereHas('classRoom', fn($q) => $q->where('tutor_id', $tutor->id))
            ->with(['classRoom.subject'])
            ->orderBy('start_time')
            ->get();

        // ── Total Siswa di semua kelas tutor ini ──────────────────────────────
        $totalStudents = \App\Models\Student::whereHas(
            'classRoom',
            fn($q) => $q->where('tutor_id', $tutor->id)
        )->where('is_active', true)->count();

        // ── Rata-rata kehadiran minggu ini ────────────────────────────────────
        $startOfWeek = now()->startOfWeek();
        $endOfWeek   = now()->endOfWeek();

        $totalAttendance = Attendance::whereHas(
            'schedule.classRoom',
            fn($q) => $q->where('tutor_id', $tutor->id)
        )->whereBetween('date', [$startOfWeek, $endOfWeek])->count();

        $hadirCount = Attendance::whereHas(
            'schedule.classRoom',
            fn($q) => $q->where('tutor_id', $tutor->id)
        )->where('status', 'hadir')
         ->whereBetween('date', [$startOfWeek, $endOfWeek])
         ->count();

        $avgAttendance = $totalAttendance > 0
            ? round(($hadirCount / $totalAttendance) * 100, 1)
            : 0;

        // ── Materi Terbaru ────────────────────────────────────────────────────
        $recentMaterials = Material::where('tutor_id', $tutor->id)
            ->with('classRoom')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ── Notifikasi untuk Tutor ─────────────────────────────────────────────
        $notifications = AppNotification::forRole('tutor')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('tutor.dashboard.index', compact(
            'schedulesToday',
            'totalStudents',
            'avgAttendance',
            'recentMaterials',
            'notifications'
        ));
    }
}
