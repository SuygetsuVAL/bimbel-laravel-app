# 📚 AozoraLearn — Ringkasan Lengkap Sesi Pengembangan

> **Proyek:** AozoraLearn — Sistem manajemen bimbingan belajar berbasis web (Laravel 13)
> **Database:** MySQL via Laragon (`aozoralearn`)
> **Stack:** PHP 8.5.4 · Laravel 13 · Tailwind CSS CDN · Chart.js · PhpSpreadsheet

---

## 1. Persiapan Awal & Lingkungan

### Konfigurasi `.env`
- Koneksi database diubah ke MySQL Laragon: `DB_CONNECTION=mysql`, `DB_DATABASE=aozoralearn`, `DB_USERNAME=root`, `DB_PASSWORD=` (kosong)

### Paket & Dependensi Tambahan
| Paket | Kegunaan |
|---|---|
| `spatie/laravel-permission` | Manajemen peran (Role) Admin & Tutor |
| `phpoffice/phpspreadsheet` | Ekspor laporan Excel (alternatif dari Maatwebsite/excel, kompatibel PHP 8.5) |

### Penanganan Error Awal
- **View `auth.login` not found** → Membuat berkas `resources/views/auth/login.blade.php`
- **Vite Manifest Not Found** → Mengganti direktif `@vite()` dengan Tailwind CSS CDN di semua layout Blade

---

## 2. Arsitektur Database (Backend)

### Tabel yang Dibuat (Migrasi)
| Tabel | Keterangan |
|---|---|
| `users` | Tambah kolom `phone`, `photo`, `is_active` |
| `tutors` | Profil pengajar (spesialisasi, gaji) |
| `subjects` | Mata pelajaran |
| `classes` | Kelas bimbel (terhubung ke tutor & subject) |
| `students` | Data siswa (NIS, orang tua, kelas) |
| `schedules` | Jadwal kelas dengan scope `today()` |
| `attendances` | Rekap kehadiran harian per siswa per jadwal |
| `materials` | File pelajaran yang diunggah tutor |
| `grades` | Nilai ujian siswa per kelas |
| `payments` | Tagihan & verifikasi SPP bulanan |
| `app_notifications` | Broadcast pengumuman ke seluruh pengguna |

### Model Eloquent & Relasi
```
User → hasOne(Tutor), hasOne(Student)
Tutor → hasMany(ClassRoom), hasMany(Material)
ClassRoom → belongsTo(Tutor), belongsTo(Subject), hasMany(Student), hasMany(Schedule)
Schedule → hasMany(Attendance), scope today()
Student → hasMany(Attendance), hasMany(Grade), hasMany(Payment)
Grade → scopenya dihitung score_percent (accessor)
AppNotification → forRole() scope
```

### Seeder Data Demo
- **`RoleSeeder`**: Membuat role `admin` & `tutor`, mendaftarkan user default:
  - `admin@aozoralearn.id` / `admin123`
  - `rudi@aozoralearn.id` / `tutor123`
- **`DemoDataSeeder`**: Populates mata pelajaran, kelas, jadwal, siswa (3 siswa: Andi, Budi, Citra), pembayaran, materi awal, dan notifikasi

---

## 3. Logika Backend (Controllers)

### Autentikasi
- `AuthController` — Login dengan redirect cerdas berdasarkan role, deteksi akun nonaktif (`is_active`), logout

### Middleware
| Alias | Kelas | Fungsi |
|---|---|---|
| `admin` | `AdminMiddleware` | Wajib login + role admin |
| `tutor` | `TutorMiddleware` | Wajib login + role tutor |

### Admin Controllers
| Controller | Modul |
|---|---|
| `Admin\DashboardController` | Statistik kelas, siswa aktif, pendapatan bulan ini, chart arus kas via Chart.js |
| `Admin\StudentController` | CRUD siswa & user terhubung |
| `Admin\TutorController` | CRUD tutor & user terhubung |
| `Admin\ClassController` | CRUD kelas |
| `Admin\ScheduleController` | CRUD jadwal mengajar |
| `Admin\PaymentController` | Filter status bayar, verifikasi, buat tagihan baru |
| `Admin\ReportController` | Ekspor Excel (Presensi, Nilai, Keuangan) via PhpSpreadsheet |
| `Admin\NotificationController` | Kirim & hapus pengumuman broadcast |

### Tutor Controllers
| Controller | Modul |
|---|---|
| `Tutor\DashboardController` | Jadwal hari ini (scope tutor), rata-rata kehadiran, materi terbaru |
| `Tutor\AttendanceController` | Input presensi multi-siswa (H/S/I/A) per jadwal |
| `Tutor\MaterialController` | Upload file (PDF, DOCX, MP4) ke `storage/app/public` |
| `Tutor\GradeController` | Input nilai per kelas + AJAX dropdown siswa |

---

## 4. Routing

```
GET  /login                        → Auth\AuthController@showLogin
POST /login                        → Auth\AuthController@login
POST /logout                       → Auth\AuthController@logout

Group /admin (middleware: auth, role:admin)
  GET  /dashboard                  → Admin\DashboardController@index
  RESOURCE /students               → Admin\StudentController
  RESOURCE /tutors                 → Admin\TutorController
  RESOURCE /classes                → Admin\ClassController
  RESOURCE /schedules              → Admin\ScheduleController
  GET|POST /payments               → Admin\PaymentController
  POST /payments/{id}/verify       → Admin\PaymentController@verify
  GET  /reports                    → Admin\ReportController@index
  GET  /reports/attendance/export  → Admin\ReportController@exportAttendance
  GET  /reports/grades/export      → Admin\ReportController@exportGrades
  GET  /reports/payments/export    → Admin\ReportController@exportPayments
  GET|POST /notifications          → Admin\NotificationController

Group /tutor (middleware: auth, role:tutor)
  GET  /dashboard                  → Tutor\DashboardController@index
  GET  /attendance                 → Tutor\AttendanceController@index
  GET  /attendance/create/{id}     → Tutor\AttendanceController@create
  POST /attendance                 → Tutor\AttendanceController@store
  RESOURCE /materials (no show)    → Tutor\MaterialController
  GET|POST /grades                 → Tutor\GradeController
  GET  /grades/students            → Tutor\GradeController@getStudentsByClass
```

---

## 5. Desain Frontend

### 5.1 Portal Admin (Tema Biru `#004ac6`)
Berdasarkan referensi desain di folder `Design Reference/Dashboard Admin/`:

| File Blade | Keterangan |
|---|---|
| `layouts/admin.blade.php` | Sidebar 280px tetap, topbar, avatar inisial, flash alert |
| `admin/dashboard/index.blade.php` | Bento Grid + Chart.js arus kas bulanan + widget statistik |
| `admin/students/*` | Tabel siswa dengan search & modal form tambah/edit |
| `admin/tutors/*` | Tabel tutor + form dengan badge spesialisasi |
| `admin/classes/*` | Manajemen kelas & mata pelajaran per tutor |
| `admin/schedules/*` | Form jadwal dengan pemilih tanggal & ruangan |
| `admin/payments/index.blade.php` | Verifikasi bayar + filter status + modal tagihan baru |
| `admin/reports/index.blade.php` | Tombol unduh laporan Excel per kategori |
| `admin/notifications/index.blade.php` | Form broadcast + daftar pengumuman aktif |

### 5.2 Portal Tutor (Awalnya Orange, Diubah ke Biru `#004ac6`)
Berdasarkan referensi desain di folder `Design Reference/Dashboard Tutor/`:

| File Blade | Keterangan |
|---|---|
| `layouts/tutor.blade.php` | Sidebar dengan avatar inisial, menu navigasi 4 modul, footer |
| `tutor/dashboard/index.blade.php` | Jadwal harian + 3 pintasan cepat + widget kehadiran & materi |
| `tutor/attendance/create.blade.php` | Radio button bulat kustom H/S/I/A + counter real-time JS |
| `tutor/attendance/index.blade.php` | Daftar jadwal kiri + riwayat log presensi kanan |
| `tutor/materials/index.blade.php` | Grid file pelajaran + stat cards |
| `tutor/materials/create.blade.php` | Form upload file dengan dropzone + deskripsi tugas |
| `tutor/materials/edit.blade.php` | Edit judul & file, tampilkan link file lama |
| `tutor/grades/index.blade.php` | Bento stats nilai + list kelas filter kiri + tabel rekap kanan |
| `tutor/grades/create.blade.php` | Form input nilai per siswa dengan AJAX dropdown kelas |

### 5.3 Halaman Login (`auth/login.blade.php`)
- Glassmorphism (`backdrop-filter: blur`)
- Dekorasi background blob biru & oranye
- Form login dengan icon Material Symbols dan tombol "Masuk ke Portal"

---

## 6. Penyesuaian Tema Warna Tutor Portal

Permintaan terakhir pengguna: **"Ubah warna seluruh dashboard tutor agar sesuai dengan tema warna dashboard admin"**

### Perubahan yang Dilakukan:
- Konfigurasi Tailwind di `layouts/tutor.blade.php`:
  - `"primary": "#EA580C"` → **`"primary": "#004ac6"`**
  - `"primary-dark": "#C2410C"` → **`"primary-dark": "#1E40AF"`**
  - `"primary-light": "#F97316"` → **`"primary-light": "#3B82F6"`**
  - `"primary-tint": "#FFF7ED"` → **`"primary-tint": "#EFF6FF"`**
- Seluruh class `text-orange-600`, `bg-orange-600`, `border-orange-*`, `hover:border-orange-200` di semua view tutor diganti menjadi `text-primary`, `bg-primary`, `border-primary`, dan `hover:border-primary-tint`.

---

## 7. Verifikasi & Pengujian Otomatis

### Suite Test yang Dibuat
File: [`tests/Feature/AozoraLearnTest.php`](file:///c:/laragon/www/aozoralearn/tests/Feature/AozoraLearnTest.php)

```
Tests\Feature\AozoraLearnTest
  ✅ test_login_page_renders
  ✅ test_root_redirects_to_login
  ✅ test_admin_can_login_and_access_all_modules  (8 assertions)
  ✅ test_tutor_can_login_and_access_all_modules  (4 assertions)
```

### Hasil Akhir
```json
{
  "result": "passed",
  "tests": 6,
  "passed": 6,
  "assertions": 20,
  "duration_ms": 2191
}
```

> ✅ **Semua 6 tes lulus — Proyek siap di-push ke GitHub!**

---

## 8. Akun Demo

| Role | Email | Password |
|---|---|---|
| Super Admin | `admin@aozoralearn.id` | `admin123` |
| Tutor Senior (Pak Rudi) | `rudi@aozoralearn.id` | `tutor123` |

---

## 9. Catatan Teknis Penting

| Topik | Keterangan |
|---|---|
| **Model Naming** | `ClassRoom` (bukan `Class`) karena `class` adalah reserved keyword PHP |
| **Tailwind Mode** | Menggunakan CDN `<script src="https://cdn.tailwindcss.com">` — tanpa build step `npm run build` |
| **Storage Link** | File materi disimpan di `storage/app/public`, diakses via `Storage::url()` |
| **Score Percent** | Accessor `score_percent` di model `Grade` = `($score / $max_score) * 100` |
| **Test DB** | `phpunit.xml` dikonfigurasi dengan SQLite in-memory + `RefreshDatabase` + `$seed = true` |

---

*Dokumen ini dihasilkan secara otomatis sebagai ringkasan sesi pengembangan penuh AozoraLearn.*
*Tanggal sesi: 7 Juli 2026*
