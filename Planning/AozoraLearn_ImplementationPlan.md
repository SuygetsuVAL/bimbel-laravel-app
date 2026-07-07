# AozoraLearn — Implementation Plan
## Platform Bimbel Online Terintegrasi · Laravel 11

> **Dokumen ini adalah rencana implementasi teknis lengkap untuk AI Agent.**
> Gunakan sebagai konteks utama saat membangun website AozoraLearn.
> Scope: Dashboard Admin + Dashboard Tutor (Desktop-first).

---

## PROJECT OVERVIEW

| Atribut | Detail |
|---|---|
| Nama Produk | AozoraLearn |
| Jenis | Platform Bimbel Online Terintegrasi |
| Target User (scope ini) | Admin (Mbak Dina) + Tutor (Pak Rudi) |
| Platform | Desktop-first (browser) |
| Framework | Laravel 11 |
| PHP Version | 8.2+ |
| Database | MySQL 8 |
| Frontend | Blade Templates + Tailwind CSS |
| Font | Inter (Google Fonts) |
| Icon Library | Lucide Icons atau Heroicons |

---

## USER PERSONAS

### Persona 1 — Admin (Mbak Dina)

```
Usia       : 25 tahun
Pekerjaan  : Admin Bimbel
Pendidikan : D3 Manajemen
Lokasi     : Bengkalis
Perangkat  : Laptop & Smartphone Android
Tech Level : Tinggi
```

**Quote:** *"Saya ingin semua data terkelola dengan rapi dan real-time agar pekerjaan lebih efisien dan keputusan lebih akurat."*

**Tujuan:**
- Mengelola data siswa, tutor, kelas, dan mata pelajaran dengan rapi
- Memantau pembayaran dan keuangan secara real-time
- Membuat jadwal kelas dan memastikan tutor mengajar sesuai jadwal
- Menyediakan laporan lengkap untuk manajemen dan orang tua
- Mengirim pengumuman dan notifikasi kepada pengguna
- Memastikan semua data terintegrasi dan mudah diakses
- Mengurangi pekerjaan manual dan meminimalkan kesalahan data

**Frustrasi:**
- Data siswa, pembayaran, dan absensi tersebar di banyak file
- Pembuatan laporan masih manual dan memakan waktu lama
- Sering terjadi kesalahan input data atau duplikasi data
- Sulit memantau pembayaran yang belum lunas
- Komunikasi ke banyak orang tua memerlukan waktu lebih
- Tidak ada sistem notifikasi terpusat untuk informasi penting
- Sistem yang digunakan sebelumnya kurang terintegrasi

**Kebutuhan Fitur:**

| Fitur | Deskripsi |
|---|---|
| Kelola Data Siswa | Tambah, ubah, hapus, dan lihat data siswa secara lengkap dan terstruktur |
| Kelola Kelas & Tutor | Mengatur kelas, mata pelajaran, tutor, dan penugasan mengajar |
| Jadwal Terintegrasi | Membuat dan mengelola jadwal kelas & tutor dengan tampilan kalender |
| Manajemen Keuangan | Memantau pembayaran, tagihan, riwayat transaksi, dan status tunggakan siswa |
| Laporan & Statistik | Membuat laporan kehadiran, nilai, pembayaran, dan progres belajar |
| Pengumuman & Notifikasi | Mengirim pengumuman, notifikasi, dan informasi penting kepada siswa, tutor, dan orang tua |

---

### Persona 2 — Tutor (Pak Rudi)

```
Usia           : 28 tahun
Pekerjaan      : Tutor Bimbel
Mata Pelajaran : Matematika
Lokasi         : Bengkalis
Perangkat      : Laptop & Smartphone Android
Tech Level     : Tinggi
```

**Quote:** *"Saya ingin mengajar lebih efektif dengan tools yang membuat pekerjaan saya lebih mudah dan cepat."*

**Tujuan:**
- Menginput kehadiran siswa dengan cepat dan akurat
- Mengupload materi, tugas, dan latihan untuk siswa
- Melihat perkembangan belajar siswa setiap kelas
- Berkomunikasi dengan siswa dan admin dengan mudah
- Mengelola jadwal mengajar secara terorganisir
- Menghemat waktu dalam pekerjaan administrasi

**Frustrasi:**
- Input kehadiran manual memakan waktu
- Sulit membagikan materi ke semua siswa secara efisien
- Tidak ada notifikasi jika ada perubahan jadwal
- Komunikasi dengan admin sering lewat WhatsApp
- Sulit memantau keaktifan siswa di kelas
- Harus menggunakan banyak aplikasi berbeda

**Kebutuhan Fitur:**

| Fitur | Deskripsi |
|---|---|
| Input Kehadiran | Mudah mencatat kehadiran siswa dengan cepat |
| Upload Materi | Menggunggah materi, file, video, dan tugas untuk siswa |
| Komunikasi | Chat dengan siswa atau admin terkait kelas dan materi |
| Jadwal Mengajar | Melihat dan mengelola jadwal mengajar setiap kelas |
| Monitoring Siswa | Melihat progres, nilai, dan keaktifan siswa di kelas |
| Notifikasi | Menerima notifikasi penting dari sistem secara real-time |

---

## DESIGN SYSTEM

### Color Palette

```css
/* Primary Colors */
--color-primary:        #2563EB;   /* Blue — CTA utama */
--color-primary-dark:   #1E40AF;   /* Blue Dark — hover */
--color-primary-light:  #3B82F6;   /* Blue Light */

/* Secondary Colors */
--color-secondary:      #F97316;   /* Orange — CTA sekunder */
--color-secondary-dark: #EA580C;
--color-secondary-light:#FB923C;

/* Neutral Colors */
--color-gray-900:       #111827;   /* Text utama */
--color-gray-700:       #374151;   /* Text secondary */
--color-gray-500:       #6B7280;   /* Text disabled */
--color-gray-300:       #D1D5DB;   /* Border */
--color-gray-100:       #F3F4F6;   /* Background light */
--color-gray-50:        #F9FAFB;   /* Background page */
--color-white:          #FFFFFF;   /* Cards, surfaces */

/* Semantic Colors */
--color-success:        #10B981;   /* Berhasil / lunas */
--color-warning:        #F59E0B;   /* Peringatan */
--color-error:          #EF4444;   /* Error / danger */
--color-info:           #3B82F6;   /* Informasi */
```

### Typography

```
Font Family : Inter (Google Fonts)
Fallback    : -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif

Desktop Scale (base 16px):
  H1   : 32px / Bold 700   / line-height 40px  → Page title
  H2   : 24px / Semibold 600 / line-height 32px → Section title
  H3   : 20px / Semibold 600 / line-height 28px → Card title
  Body : 16px / Regular 400 / line-height 24px  → Body text
  Cap  : 14px / Regular 400 / line-height 20px  → Helper, labels
```

### Layout Rules (Desktop)

```
Sidebar Width  : 250px (fixed left)
Content Area   : calc(100vw - 250px)
Page Padding   : 24px
Card Padding   : 24px
Card Radius    : 16px
Card Shadow    : 0 4px 12px rgba(0,0,0,0.08)
Gap Between    : 16px
Min Touch      : 44x44px
```

### Button Specs

```
Primary Button:
  height: 44px | radius: 8px | bg: #2563EB | text: white 14px semibold
  hover: bg #1E40AF | disabled: bg #93C5FD

Secondary Button:
  bg: transparent | border: 2px solid #2563EB | text: #2563EB
  hover: bg #EFF6FF

Danger Button:
  bg: #EF4444 | text: white | hover: bg #DC2626
```

### Sidebar Navigation (Active State)

```css
.nav-item-active {
  background: #EFF6FF;
  color: #2563EB;
  border-left: 4px solid #2563EB;
}
.nav-item:hover {
  background: #F9FAFB;
}
```

---

## TECH STACK

| Layer | Technology | Package / Version |
|---|---|---|
| Backend Framework | Laravel | 11.x |
| PHP | PHP | 8.2+ |
| Database | MySQL | 8.0 |
| ORM | Eloquent | (built-in) |
| Frontend CSS | Tailwind CSS | 3.x |
| Template Engine | Laravel Blade | (built-in) |
| Auth & Roles | Spatie Permission | ^6.0 |
| Charts | Chart.js | 4.x (CDN) |
| File Upload | Laravel Storage | (built-in) |
| Export Excel | Maatwebsite Excel | ^3.1 |
| Icons | Lucide Icons | (CDN/npm) |
| Form Validation | Laravel Validator | (built-in) |
| Pagination | Laravel Pagination | (built-in) |

### Install Commands

```bash
# 1. Buat project baru
composer create-project laravel/laravel aozoralearn

# 2. Install dependencies utama
composer require spatie/laravel-permission
composer require maatwebsite/excel

# 3. Install frontend
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init -p

# 4. Publish config Spatie
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

# 5. Jalankan migration awal
php artisan migrate
```

---

## DATABASE SCHEMA

### Tabel: users

```sql
CREATE TABLE users (
  id            BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name          VARCHAR(255) NOT NULL,
  email         VARCHAR(255) UNIQUE NOT NULL,
  password      VARCHAR(255) NOT NULL,
  phone         VARCHAR(20),
  photo         VARCHAR(255),
  is_active     BOOLEAN DEFAULT TRUE,
  remember_token VARCHAR(100),
  created_at    TIMESTAMP,
  updated_at    TIMESTAMP
);
-- Roles via Spatie: 'admin', 'tutor', 'student', 'parent'
```

### Tabel: students

```sql
CREATE TABLE students (
  id            BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id       BIGINT UNSIGNED REFERENCES users(id),
  nis           VARCHAR(20) UNIQUE NOT NULL,
  class_id      BIGINT UNSIGNED REFERENCES classes(id),
  parent_name   VARCHAR(255),
  parent_phone  VARCHAR(20),
  parent_email  VARCHAR(255),
  address       TEXT,
  birth_date    DATE,
  join_date     DATE,
  is_active     BOOLEAN DEFAULT TRUE,
  created_at    TIMESTAMP,
  updated_at    TIMESTAMP
);
```

### Tabel: tutors

```sql
CREATE TABLE tutors (
  id              BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  user_id         BIGINT UNSIGNED REFERENCES users(id),
  nip             VARCHAR(20) UNIQUE,
  specialization  VARCHAR(255),
  education       VARCHAR(255),
  join_date       DATE,
  is_active       BOOLEAN DEFAULT TRUE,
  created_at      TIMESTAMP,
  updated_at      TIMESTAMP
);
```

### Tabel: subjects

```sql
CREATE TABLE subjects (
  id        BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name      VARCHAR(255) NOT NULL,  -- Matematika, B.Inggris, B.Jepang
  code      VARCHAR(20),
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
```

### Tabel: classes

```sql
CREATE TABLE classes (
  id           BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  name         VARCHAR(255) NOT NULL,  -- Kelas A, Kelas B
  level        VARCHAR(50),            -- X IPA, XI IPS, dst
  subject_id   BIGINT UNSIGNED REFERENCES subjects(id),
  tutor_id     BIGINT UNSIGNED REFERENCES tutors(id),
  max_students INT DEFAULT 20,
  is_active    BOOLEAN DEFAULT TRUE,
  created_at   TIMESTAMP,
  updated_at   TIMESTAMP
);
```

### Tabel: schedules

```sql
CREATE TABLE schedules (
  id           BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  class_id     BIGINT UNSIGNED REFERENCES classes(id),
  day_of_week  ENUM('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'),
  start_time   TIME NOT NULL,          -- 08:00
  end_time     TIME NOT NULL,          -- 09:30
  room         VARCHAR(100),
  is_active    BOOLEAN DEFAULT TRUE,
  created_at   TIMESTAMP,
  updated_at   TIMESTAMP
);
```

### Tabel: attendances

```sql
CREATE TABLE attendances (
  id          BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  student_id  BIGINT UNSIGNED REFERENCES students(id),
  schedule_id BIGINT UNSIGNED REFERENCES schedules(id),
  date        DATE NOT NULL,
  status      ENUM('hadir','sakit','izin','alpha') NOT NULL,
  notes       TEXT,
  recorded_by BIGINT UNSIGNED REFERENCES users(id),  -- tutor yg input
  created_at  TIMESTAMP,
  updated_at  TIMESTAMP,
  UNIQUE KEY unique_attendance (student_id, schedule_id, date)
);
```

### Tabel: materials

```sql
CREATE TABLE materials (
  id          BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  class_id    BIGINT UNSIGNED REFERENCES classes(id),
  tutor_id    BIGINT UNSIGNED REFERENCES tutors(id),
  title       VARCHAR(255) NOT NULL,
  description TEXT,
  file_path   VARCHAR(500),
  file_type   ENUM('pdf','doc','video','link','other'),
  is_published BOOLEAN DEFAULT TRUE,
  created_at  TIMESTAMP,
  updated_at  TIMESTAMP
);
```

### Tabel: grades

```sql
CREATE TABLE grades (
  id          BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  student_id  BIGINT UNSIGNED REFERENCES students(id),
  class_id    BIGINT UNSIGNED REFERENCES classes(id),
  exam_type   ENUM('UH','UTS','UAS','Tugas','Quiz'),
  score       DECIMAL(5,2),
  max_score   DECIMAL(5,2) DEFAULT 100,
  notes       TEXT,
  graded_by   BIGINT UNSIGNED REFERENCES users(id),
  graded_at   DATE,
  created_at  TIMESTAMP,
  updated_at  TIMESTAMP
);
```

### Tabel: payments

```sql
CREATE TABLE payments (
  id           BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  student_id   BIGINT UNSIGNED REFERENCES students(id),
  period       VARCHAR(20) NOT NULL,      -- '2026-06' (YYYY-MM)
  amount       DECIMAL(12,2) NOT NULL,
  method       ENUM('bca','mandiri','gopay','ovo','tunai'),
  status       ENUM('belum_lunas','lunas','cicilan') DEFAULT 'belum_lunas',
  paid_at      TIMESTAMP NULL,
  verified_by  BIGINT UNSIGNED REFERENCES users(id),
  notes        TEXT,
  created_at   TIMESTAMP,
  updated_at   TIMESTAMP,
  UNIQUE KEY unique_payment (student_id, period)
);
```

### Tabel: notifications

```sql
CREATE TABLE notifications (
  id           BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
  title        VARCHAR(255) NOT NULL,
  message      TEXT NOT NULL,
  target_role  ENUM('all','admin','tutor','student','parent'),
  target_id    BIGINT UNSIGNED NULL,     -- null = broadcast ke semua role
  is_read      BOOLEAN DEFAULT FALSE,
  sent_by      BIGINT UNSIGNED REFERENCES users(id),
  created_at   TIMESTAMP,
  updated_at   TIMESTAMP
);
```

---

## FOLDER STRUCTURE

```
aozoralearn/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   └── AuthController.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── StudentController.php
│   │   │   │   ├── TutorController.php
│   │   │   │   ├── ClassController.php
│   │   │   │   ├── ScheduleController.php
│   │   │   │   ├── PaymentController.php
│   │   │   │   ├── ReportController.php
│   │   │   │   └── NotificationController.php
│   │   │   └── Tutor/
│   │   │       ├── DashboardController.php
│   │   │       ├── AttendanceController.php
│   │   │       ├── MaterialController.php
│   │   │       └── GradeController.php
│   │   ├── Middleware/
│   │   │   ├── AdminMiddleware.php
│   │   │   └── TutorMiddleware.php
│   │   └── Requests/
│   │       ├── StoreStudentRequest.php
│   │       ├── StoreAttendanceRequest.php
│   │       └── StoreMaterialRequest.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Student.php
│   │   ├── Tutor.php
│   │   ├── Subject.php
│   │   ├── ClassRoom.php
│   │   ├── Schedule.php
│   │   ├── Attendance.php
│   │   ├── Material.php
│   │   ├── Grade.php
│   │   ├── Payment.php
│   │   └── Notification.php
│   └── Exports/
│       ├── AttendanceExport.php
│       ├── GradesExport.php
│       └── PaymentsExport.php
│
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_students_table.php
│   │   ├── create_tutors_table.php
│   │   ├── create_subjects_table.php
│   │   ├── create_classes_table.php
│   │   ├── create_schedules_table.php
│   │   ├── create_attendances_table.php
│   │   ├── create_materials_table.php
│   │   ├── create_grades_table.php
│   │   ├── create_payments_table.php
│   │   └── create_notifications_table.php
│   └── seeders/
│       ├── RoleSeeder.php
│       ├── AdminUserSeeder.php
│       └── DemoDataSeeder.php
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── admin.blade.php        ← layout sidebar admin
│       │   ├── tutor.blade.php        ← layout sidebar tutor
│       │   └── auth.blade.php         ← layout halaman login
│       ├── auth/
│       │   └── login.blade.php
│       ├── admin/
│       │   ├── dashboard/
│       │   │   └── index.blade.php
│       │   ├── students/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── tutors/
│       │   │   ├── index.blade.php
│       │   │   ├── create.blade.php
│       │   │   └── edit.blade.php
│       │   ├── classes/
│       │   │   ├── index.blade.php
│       │   │   └── create.blade.php
│       │   ├── schedules/
│       │   │   └── index.blade.php
│       │   ├── payments/
│       │   │   └── index.blade.php
│       │   └── reports/
│       │       └── index.blade.php
│       ├── tutor/
│       │   ├── dashboard/
│       │   │   └── index.blade.php
│       │   ├── attendance/
│       │   │   ├── index.blade.php
│       │   │   └── create.blade.php   ← form input kehadiran
│       │   ├── materials/
│       │   │   ├── index.blade.php
│       │   │   └── create.blade.php
│       │   └── grades/
│       │       ├── index.blade.php
│       │       └── create.blade.php
│       └── components/
│           ├── stat-card.blade.php
│           ├── alert.blade.php
│           └── confirm-modal.blade.php
│
└── routes/
    └── web.php
```

---

## ROUTING

```php
<?php
// routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Tutor;

// ─── AUTH ────────────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── ADMIN ───────────────────────────────────────────────────────────────────
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

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
    Route::resource('notifications', Admin\NotificationController::class)
        ->only(['index','store','destroy']);
});

// ─── TUTOR ───────────────────────────────────────────────────────────────────
Route::prefix('tutor')
    ->middleware(['auth', 'role:tutor'])
    ->name('tutor.')
    ->group(function () {

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
    Route::resource('materials', Tutor\MaterialController::class)
        ->except(['show']);

    // Nilai
    Route::get('/grades', [Tutor\GradeController::class, 'index'])
        ->name('grades.index');
    Route::get('/grades/create', [Tutor\GradeController::class, 'create'])
        ->name('grades.create');
    Route::post('/grades', [Tutor\GradeController::class, 'store'])
        ->name('grades.store');
});

// ─── ROOT REDIRECT ───────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->hasRole('admin')
            ? redirect()->route('admin.dashboard')
            : redirect()->route('tutor.dashboard');
    }
    return redirect()->route('login');
});
```

---

## AUTH CONTROLLER

```php
<?php
// app/Http/Controllers/Auth/AuthController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('tutor')) {
                return redirect()->route('tutor.dashboard');
            }

            Auth::logout();
            return back()->withErrors(['email' => 'Role tidak dikenali.']);
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
```

---

## BLADE LAYOUTS

### Layout Admin (`layouts/admin.blade.php`)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AozoraLearn') — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-inter">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside class="w-[250px] bg-white border-r border-gray-200 flex flex-col flex-shrink-0 overflow-y-auto">

        <!-- Logo -->
        <div class="px-5 py-5 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">A</span>
                </div>
                <span class="font-semibold text-gray-900 text-base">AozoraLearn</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1">

            <!-- Tambah Data -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-3 mb-2">
                Tambah Data
            </p>
            <a href="{{ route('admin.students.create') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.students.create') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : '' }}">
                + Tambah Siswa
            </a>
            <a href="{{ route('admin.tutors.create') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.tutors.create') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : '' }}">
                + Tambah Tutor
            </a>
            <a href="{{ route('admin.schedules.create') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                + Tambah Jadwal
            </a>

            <!-- Kelola Data -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-3 mt-4 mb-2">
                Kelola Data
            </p>
            <a href="{{ route('admin.students.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.students.*') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : '' }}">
                Data Siswa
            </a>
            <a href="{{ route('admin.tutors.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.tutors.*') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : '' }}">
                Data Tutor
            </a>
            <a href="{{ route('admin.classes.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                Data Kelas
            </a>
            <a href="{{ route('admin.schedules.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                Data Jadwal
            </a>

            <!-- Keuangan -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-3 mt-4 mb-2">
                Keuangan
            </p>
            <a href="{{ route('admin.payments.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50 {{ request()->routeIs('admin.payments.*') ? 'bg-blue-50 text-blue-600 border-l-4 border-blue-600' : '' }}">
                Pembayaran
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                Laporan & Export
            </a>

            <!-- Pengaturan -->
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide px-3 mt-4 mb-2">
                Sistem
            </p>
            <a href="{{ route('admin.notifications.index') }}"
               class="flex items-center gap-3 px-3 py-3 rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                Notifikasi
            </a>
        </nav>

        <!-- User Info + Logout -->
        <div class="px-3 py-4 border-t border-gray-200">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-semibold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-gray-500">Admin</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Topbar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
            <div class="flex items-center gap-4">
                <!-- Notification Bell -->
                <button class="relative p-2 text-gray-500 hover:text-gray-900">
                    <span class="text-lg">🔔</span>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <span class="text-sm text-gray-600">Admin</span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-6">

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2">
                ⚠️ {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
```

---

## HALAMAN UTAMA

### Dashboard Admin (`admin/dashboard/index.blade.php`)

```html
@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Selamat Datang, Admin!')

@section('content')

<!-- Stat Cards -->
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Total Siswa Aktif</p>
        <p class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Total Tutor Aktif</p>
        <p class="text-3xl font-bold text-gray-900">{{ $totalTutors }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Kelas Hari Ini</p>
        <p class="text-3xl font-bold text-blue-600">{{ $classesToday }}</p>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <p class="text-sm text-gray-500 mb-1">Pembayaran Baru</p>
        <p class="text-3xl font-bold text-green-600">Rp {{ number_format($newPayments) }}</p>
    </div>
</div>

<!-- Pengingat + Grafik -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <!-- Pengingat -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
        <h3 class="font-semibold text-yellow-800 mb-3">⚠️ Pengingat</h3>
        @foreach($reminders as $reminder)
        <p class="text-sm text-yellow-700 mb-1">— {{ $reminder }}</p>
        @endforeach
    </div>
    <!-- Grafik Pembayaran -->
    <div class="col-span-2 bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
        <h3 class="font-semibold text-gray-900 mb-4">Grafik Pembayaran Bulan Ini</h3>
        <canvas id="paymentChart" height="120"></canvas>
    </div>
</div>

<!-- Aktivitas Terbaru -->
<div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm">
    <h3 class="font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
    <div class="space-y-3">
        @foreach($recentActivities as $activity)
        <div class="flex items-center gap-3 text-sm text-gray-600 border-b border-gray-50 pb-2">
            <span class="text-gray-400 text-xs">{{ $activity->created_at->diffForHumans() }}</span>
            <span>{{ $activity->message }}</span>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
new Chart(document.getElementById('paymentChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Pembayaran (Rp)',
            data: {!! json_encode($chartData) !!},
            backgroundColor: '#2563EB',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>
@endpush
```

### Form Input Kehadiran (`tutor/attendance/create.blade.php`)

```html
@extends('layouts.tutor')
@section('title', 'Input Kehadiran')
@section('page-title', 'Input Kehadiran Siswa')

@section('content')
<div class="max-w-4xl">

    <!-- Info Kelas -->
    <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm mb-5">
        <div class="grid grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Kelas</p>
                <p class="font-semibold text-gray-900">{{ $schedule->class->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal</p>
                <p class="font-semibold text-gray-900">{{ now()->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Waktu</p>
                <p class="font-semibold text-gray-900">
                    {{ $schedule->start_time }} — {{ $schedule->end_time }}
                </p>
            </div>
        </div>
    </div>

    <!-- Form Kehadiran -->
    <form action="{{ route('tutor.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
        <input type="hidden" name="date" value="{{ now()->toDateString() }}">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-5 py-3 text-left text-gray-600 font-semibold w-12">No</th>
                        <th class="px-5 py-3 text-left text-gray-600 font-semibold">Nama Siswa</th>
                        <th class="px-5 py-3 text-center text-gray-600 font-semibold">Hadir</th>
                        <th class="px-5 py-3 text-center text-gray-600 font-semibold">Sakit</th>
                        <th class="px-5 py-3 text-center text-gray-600 font-semibold">Izin</th>
                        <th class="px-5 py-3 text-center text-gray-600 font-semibold">Alpha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                        <td class="px-5 py-4 text-gray-500">{{ $i + 1 }}</td>
                        <td class="px-5 py-4 font-medium text-gray-900">{{ $student->user->name }}</td>
                        @foreach(['hadir','sakit','izin','alpha'] as $status)
                        <td class="px-5 py-4 text-center">
                            <input type="radio"
                                name="attendance[{{ $student->id }}]"
                                value="{{ $status }}"
                                {{ $status === 'hadir' ? 'checked' : '' }}
                                class="w-4 h-4 accent-blue-600">
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Rekap Live (JS) -->
        <div id="rekap" class="bg-gray-50 rounded-xl p-4 border border-gray-200 mb-5 text-sm">
            <span class="font-medium text-gray-700">Rekap:</span>
            <span class="ml-3 text-green-600">Hadir: <strong id="cnt-hadir">0</strong></span>
            <span class="ml-3 text-yellow-600">Sakit: <strong id="cnt-sakit">0</strong></span>
            <span class="ml-3 text-blue-600">Izin: <strong id="cnt-izin">0</strong></span>
            <span class="ml-3 text-red-600">Alpha: <strong id="cnt-alpha">0</strong></span>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('tutor.attendance.index') }}"
               class="px-5 py-2.5 border-2 border-blue-600 text-blue-600 rounded-lg text-sm font-semibold hover:bg-blue-50">
                Batal
            </a>
            <button type="submit"
                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
                Simpan Kehadiran
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function updateRekap() {
    const statuses = ['hadir','sakit','izin','alpha'];
    statuses.forEach(s => {
        const count = document.querySelectorAll(
            `input[type=radio][value="${s}"]:checked`
        ).length;
        document.getElementById(`cnt-${s}`).textContent = count;
    });
}
document.querySelectorAll('input[type=radio]')
    .forEach(r => r.addEventListener('change', updateRekap));
updateRekap();
</script>
@endpush
```

---

## SEEDER & ROLES

```php
<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat roles
        $adminRole  = Role::create(['name' => 'admin']);
        $tutorRole  = Role::create(['name' => 'tutor']);

        // Buat user admin default
        $admin = User::create([
            'name'     => 'Mbak Dina',
            'email'    => 'admin@aozoralearn.id',
            'password' => bcrypt('admin123'),
        ]);
        $admin->assignRole('admin');

        // Buat user tutor contoh
        $tutor = User::create([
            'name'     => 'Pak Rudi',
            'email'    => 'rudi@aozoralearn.id',
            'password' => bcrypt('tutor123'),
        ]);
        $tutor->assignRole('tutor');
    }
}
```

---

## INTERACTION PATTERNS

### Toast Notification (Blade Component)

```html
<!-- resources/views/components/alert.blade.php -->
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
     class="fixed top-4 right-4 z-50 bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 text-sm">
    ✅ {{ session('success') }}
</div>
@endif
```

### Confirm Delete Dialog

```html
<!-- Tombol delete dengan konfirmasi -->
<form action="{{ route('admin.students.destroy', $student->id) }}"
      method="POST"
      onsubmit="return confirm('Hapus data siswa ini? Data yang dihapus tidak dapat dikembalikan.')">
    @csrf
    @method('DELETE')
    <button type="submit" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600">
        Hapus
    </button>
</form>
```

### Empty State

```html
<!-- Gunakan ketika tabel kosong -->
@if($students->isEmpty())
<div class="text-center py-16 text-gray-400">
    <p class="text-4xl mb-3">📋</p>
    <p class="font-medium text-gray-600">Belum ada data siswa</p>
    <p class="text-sm mb-4">Silakan tambahkan siswa baru</p>
    <a href="{{ route('admin.students.create') }}"
       class="px-5 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">
        + Tambah Siswa
    </a>
</div>
@endif
```

---

## VALIDATION RULES

```php
<?php
// app/Http/Requests/StoreStudentRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6',
            'nis'           => 'required|unique:students,nis',
            'class_id'      => 'required|exists:classes,id',
            'parent_name'   => 'nullable|string|max:255',
            'parent_phone'  => 'nullable|string|max:20',
            'birth_date'    => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Nama siswa wajib diisi.',
            'email.required'   => 'Email wajib diisi.',
            'email.unique'     => 'Email sudah digunakan.',
            'nis.unique'       => 'NIS sudah terdaftar.',
            'class_id.exists'  => 'Kelas tidak ditemukan.',
        ];
    }
}
```

---

## TAILWIND CONFIG

```javascript
// tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      fontFamily: {
        inter: ['Inter', 'sans-serif'],
      },
      colors: {
        primary: {
          DEFAULT: '#2563EB',
          dark:    '#1E40AF',
          light:   '#3B82F6',
        },
        secondary: {
          DEFAULT: '#F97316',
          dark:    '#EA580C',
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

---

## DEPLOYMENT CHECKLIST

### Environment (.env)

```env
APP_NAME=AozoraLearn
APP_ENV=production
APP_DEBUG=false
APP_URL=https://aozoralearn.id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aozoralearn
DB_USERNAME=root
DB_PASSWORD=your_secure_password

FILESYSTEM_DISK=local
SESSION_DRIVER=database
CACHE_STORE=database
```

### Production Commands

```bash
# Optimasi untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Storage link untuk file upload
php artisan storage:link

# Jalankan seeder
php artisan db:seed --class=RoleSeeder

# Build assets
npm run build
```

---

## IMPLEMENTATION PHASES

### Fase 1 — Setup & Auth (Minggu 1–2)
- [ ] `composer create-project laravel/laravel aozoralearn`
- [ ] Install Tailwind CSS + Inter font
- [ ] Install Spatie Permission & setup roles
- [ ] Buat semua migration dan jalankan `php artisan migrate`
- [ ] Buat `AuthController` dengan redirect per role
- [ ] Buat halaman `auth/login.blade.php` sesuai wireframe
- [ ] Buat `AdminMiddleware` dan `TutorMiddleware`
- [ ] Buat `RoleSeeder` dengan akun default admin & tutor
- [ ] Test login + redirect ke dashboard masing-masing role

### Fase 2 — Dashboard Admin (Minggu 3–4)
- [ ] Buat `layouts/admin.blade.php` (sidebar 250px, topbar, content area)
- [ ] Dashboard: stat cards (siswa, tutor, kelas, pembayaran)
- [ ] Dashboard: grafik pembayaran dengan Chart.js
- [ ] Dashboard: widget pengingat (siswa belum bayar, kelas belum ada tutor)
- [ ] CRUD Data Siswa (index, create, edit, delete + validasi)
- [ ] CRUD Data Tutor (index, create, edit, delete)
- [ ] CRUD Kelas & penugasan tutor ke kelas
- [ ] Manajemen jadwal (hari, jam mulai, jam selesai, ruangan)
- [ ] Halaman pembayaran: list tagihan, filter status, verifikasi bayar
- [ ] Laporan & export Excel (kehadiran, nilai, pembayaran)
- [ ] Halaman notifikasi: kirim pengumuman ke semua tutor/siswa
- [ ] Aktivitas terbaru di dashboard

### Fase 3 — Dashboard Tutor (Minggu 5–6)
- [ ] Buat `layouts/tutor.blade.php` (sidebar berbeda dari admin)
- [ ] Dashboard Tutor: jadwal hari ini (kartu per sesi dengan tombol aksi)
- [ ] Dashboard Tutor: statistik mingguan (rata-rata kehadiran, total siswa)
- [ ] Dashboard Tutor: materi terbaru yang diupload
- [ ] Halaman jadwal: view mingguan dan bulanan
- [ ] Form input kehadiran: tabel siswa + radio button + rekap live (JS)
- [ ] Halaman arsip kehadiran: filter per kelas dan tanggal
- [ ] Upload materi: form + file storage + list materi per kelas
- [ ] Input nilai: pilih siswa, jenis ujian, skor, catatan
- [ ] Rekap nilai: tabel per kelas dengan rata-rata
- [ ] Monitoring siswa: progres kehadiran & nilai per siswa

### Fase 4 — Polish & Deploy (Minggu 7–10)
- [ ] Tambahkan toast notification untuk semua aksi (simpan, hapus, update)
- [ ] Loading state pada tombol submit
- [ ] Empty state pada tabel/daftar yang kosong
- [ ] Confirmation dialog sebelum hapus data
- [ ] Form validation error messages (inline, di bawah input)
- [ ] Pagination pada semua tabel data besar
- [ ] Optimasi query N+1 dengan `eager loading` (`with()`)
- [ ] Responsif untuk layar tablet (min-width 768px)
- [ ] Test semua fitur end-to-end
- [ ] Setup server production (VPS/shared hosting)
- [ ] Jalankan `php artisan optimize` & `npm run build`
- [ ] Dokumentasi singkat untuk admin

---

## NOTES FOR AI AGENT

1. **Bahasa Indonesia** — semua teks UI (label, placeholder, pesan error, pesan sukses) menggunakan Bahasa Indonesia.
2. **Role-based access** — gunakan `@can` atau `$user->hasRole('admin')` untuk guard setiap fitur, jangan biarkan tutor mengakses route admin.
3. **File upload** — simpan materi ke `storage/app/public/materials/`, panggil `php artisan storage:link` agar bisa diakses via URL.
4. **Konsistensi warna** — selalu gunakan warna dari design system: primary `#2563EB`, secondary `#F97316`, success `#10B981`, error `#EF4444`.
5. **Sidebar aktif** — gunakan `request()->routeIs('admin.students.*')` untuk menandai menu aktif di sidebar.
6. **Export Excel** — gunakan package `maatwebsite/excel` dengan class di `app/Exports/`, bukan generate manual.
7. **Scope tutor** — tutor hanya boleh lihat data dari kelas yang dia ajar (`where('tutor_id', auth()->user()->tutor->id)`), bukan semua data.
8. **Timezone** — set `APP_TIMEZONE=Asia/Jakarta` di `.env` dan `config/app.php`.
9. **Pagination** — gunakan `->paginate(20)` pada semua query tabel, bukan `->get()` yang bisa slow jika data banyak.
10. **Wireframe reference** — struktur halaman mengacu pada wireframe ASCII di file `Wireframe_AozoraLearn.txt`. Dashboard Admin = Wireframe 2, Dashboard Tutor = Wireframe 3, Form Kehadiran = Wireframe 4.

---

*Dokumen ini dibuat untuk keperluan implementasi AozoraLearn menggunakan AI Agent.*
*Versi: 1.0 | Platform: Laravel 11 | Scope: Admin + Tutor Dashboard*
