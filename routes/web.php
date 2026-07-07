<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Tutor;

// ─── AUTH ─────────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── ADMIN ────────────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    // Siswa
    Route::resource('students', Admin\StudentController::class);

    // Tutor
    Route::resource('tutors', Admin\TutorController::class);

    // Kelas
    Route::resource('classes', Admin\ClassController::class);

    // Jadwal
    Route::resource('schedules', Admin\ScheduleController::class);

    // Pembayaran
    Route::get('/payments', [Admin\PaymentController::class, 'index'])
        ->name('payments.index');
    Route::post('/payments', [Admin\PaymentController::class, 'store'])
        ->name('payments.store');
    Route::post('/payments/{id}/verify', [Admin\PaymentController::class, 'verify'])
        ->name('payments.verify');

    // Laporan & Export
    Route::get('/reports', [Admin\ReportController::class, 'index'])
        ->name('reports.index');
    Route::get('/reports/attendance/export', [Admin\ReportController::class, 'exportAttendance'])
        ->name('reports.attendance.export');
    Route::get('/reports/grades/export', [Admin\ReportController::class, 'exportGrades'])
        ->name('reports.grades.export');
    Route::get('/reports/payments/export', [Admin\ReportController::class, 'exportPayments'])
        ->name('reports.payments.export');

    // Notifikasi
    Route::get('/notifications', [Admin\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications', [Admin\NotificationController::class, 'store'])
        ->name('notifications.store');
    Route::delete('/notifications/{notification}', [Admin\NotificationController::class, 'destroy'])
        ->name('notifications.destroy');
});

// ─── TUTOR ────────────────────────────────────────────────────────────────────
Route::prefix('tutor')
    ->middleware(['auth', 'role:tutor'])
    ->name('tutor.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', [Tutor\DashboardController::class, 'index'])
        ->name('dashboard');

    // Kehadiran
    Route::get('/attendance', [Tutor\AttendanceController::class, 'index'])
        ->name('attendance.index');
    Route::get('/attendance/create/{schedule_id}', [Tutor\AttendanceController::class, 'create'])
        ->name('attendance.create');
    Route::post('/attendance', [Tutor\AttendanceController::class, 'store'])
        ->name('attendance.store');

    // Materi
    Route::resource('materials', Tutor\MaterialController::class)->except(['show']);

    // Nilai
    Route::get('/grades', [Tutor\GradeController::class, 'index'])
        ->name('grades.index');
    Route::get('/grades/create', [Tutor\GradeController::class, 'create'])
        ->name('grades.create');
    Route::post('/grades', [Tutor\GradeController::class, 'store'])
        ->name('grades.store');
    Route::get('/grades/students', [Tutor\GradeController::class, 'getStudentsByClass'])
        ->name('grades.students');
});

// ─── ROOT REDIRECT ────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('tutor')) {
            return redirect()->route('tutor.dashboard');
        }
    }
    return redirect()->route('login');
});
