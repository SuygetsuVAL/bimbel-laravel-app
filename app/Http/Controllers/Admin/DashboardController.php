<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Schedule;
use App\Models\Payment;
use App\Models\AppNotification;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ─────────────────────────────────────────────────────────
        $totalStudents = Student::where('is_active', true)->count();
        $totalTutors   = Tutor::where('is_active', true)->count();

        // Kelas yang ada jadwalnya hari ini
        $classesToday = Schedule::today()
            ->with('classRoom')
            ->count();

        // Total pembayaran bulan ini yang sudah lunas
        $currentPeriod  = now()->format('Y-m');
        $newPayments    = Payment::byPeriod($currentPeriod)->paid()->sum('amount');

        // ── Widget Pengingat ──────────────────────────────────────────────────
        $reminders = [];

        $unpaidCount = Payment::byPeriod($currentPeriod)->unpaid()->count();
        if ($unpaidCount > 0) {
            $reminders[] = "{$unpaidCount} siswa belum membayar bulan " . now()->format('F Y') . '.';
        }

        $classesWithoutTutor = \App\Models\ClassRoom::where('is_active', true)
            ->whereNull('tutor_id')
            ->count();
        if ($classesWithoutTutor > 0) {
            $reminders[] = "{$classesWithoutTutor} kelas belum memiliki tutor.";
        }

        // ── Aktivitas Terbaru ─────────────────────────────────────────────────
        $recentActivities = AppNotification::orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ── Data Grafik Pembayaran (7 hari terakhir) ──────────────────────────
        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[]   = Payment::paid()
                ->whereDate('paid_at', $date->toDateString())
                ->sum('amount');
        }

        return view('admin.dashboard.index', compact(
            'totalStudents',
            'totalTutors',
            'classesToday',
            'newPayments',
            'reminders',
            'recentActivities',
            'chartLabels',
            'chartData'
        ));
    }
}
