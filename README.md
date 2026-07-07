<div align="center">

# 📚 AozoraLearn

### Sistem Manajemen Bimbingan Belajar Berbasis Web

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-CDN-06B6D4?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

> **AozoraLearn** adalah aplikasi manajemen bimbingan belajar (*bimbel*) yang dibangun dengan **Laravel 13**. Dirancang untuk mengelola seluruh operasional lembaga bimbel — mulai dari data siswa, jadwal mengajar, presensi, nilai, hingga pembayaran SPP — dalam satu platform terpadu.

</div>

---

## ✨ Fitur Utama

### 🔐 Autentikasi & Otorisasi
- Login berbasis **role** (Admin / Tutor)
- Redirect otomatis ke dashboard sesuai role
- Manajemen akun aktif/nonaktif
- Proteksi route via **middleware** per peran

### 👑 Portal Admin
| Modul | Deskripsi |
|---|---|
| 📊 **Dashboard** | Statistik real-time: total kelas, siswa aktif, pendapatan bulan ini + Chart.js arus kas |
| 👨‍🎓 **Manajemen Siswa** | CRUD siswa lengkap dengan data NIS, orang tua, dan kelas |
| 👨‍🏫 **Manajemen Tutor** | CRUD tutor dengan profil spesialisasi & pengelolaan akun |
| 🏫 **Manajemen Kelas** | Pengelolaan kelas bimbel — terhubung ke tutor & mata pelajaran |
| 🗓️ **Jadwal Mengajar** | CRUD jadwal dengan pemilih tanggal & ruangan |
| 💳 **Pembayaran SPP** | Verifikasi tagihan, buat tagihan baru, filter status bayar |
| 📊 **Laporan Excel** | Ekspor laporan Presensi, Nilai, dan Keuangan ke format `.xlsx` |
| 📢 **Notifikasi** | Broadcast pengumuman ke seluruh pengguna sistem |

### 👨‍🏫 Portal Tutor
| Modul | Deskripsi |
|---|---|
| 📅 **Dashboard Tutor** | Jadwal hari ini, rata-rata kehadiran, materi terbaru |
| ✅ **Input Presensi** | Absensi multi-siswa (Hadir/Sakit/Izin/Alpa) per jadwal |
| 📁 **Upload Materi** | Upload file PDF, DOCX, MP4 ke server |
| 📝 **Input Nilai** | Penilaian per kelas dengan AJAX dropdown siswa |

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend Framework** | [Laravel 13](https://laravel.com) |
| **PHP Version** | PHP 8.3+ |
| **Database** | MySQL 8.0 |
| **Frontend Styling** | Tailwind CSS (via CDN) |
| **Chart & Visualisasi** | [Chart.js](https://chartjs.org) |
| **Ekspor Excel** | [PhpSpreadsheet](https://phpspreadsheet.readthedocs.io) |
| **Role & Permission** | [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) |
| **Local Dev Server** | [Laragon](https://laragon.org) |

---

## 📐 Arsitektur Database

```
users             → Autentikasi + profil (phone, photo, is_active)
├── tutors        → Profil pengajar (spesialisasi, gaji)
└── students      → Data siswa (NIS, orang tua, kelas)

subjects          → Mata pelajaran
classes           → Kelas bimbel (relasi ke tutor & subject)
schedules         → Jadwal kelas (scope: today())
attendances       → Rekap kehadiran harian per siswa per jadwal
materials         → File pelajaran yang diunggah tutor
grades            → Nilai ujian siswa (accessor: score_percent)
payments          → Tagihan & verifikasi SPP bulanan
app_notifications → Broadcast pengumuman ke seluruh pengguna
```

**Relasi Eloquent utama:**
```
User         → hasOne(Tutor), hasOne(Student)
Tutor        → hasMany(ClassRoom), hasMany(Material)
ClassRoom    → belongsTo(Tutor), belongsTo(Subject), hasMany(Student), hasMany(Schedule)
Schedule     → hasMany(Attendance), scope: today()
Student      → hasMany(Attendance), hasMany(Grade), hasMany(Payment)
```

---

## 🚀 Panduan Instalasi

### Prasyarat
- [PHP 8.3+](https://php.net/downloads)
- [Composer](https://getcomposer.org)
- [Node.js & NPM](https://nodejs.org)
- [MySQL 8.0+](https://mysql.com) atau [Laragon](https://laragon.org) *(direkomendasikan)*

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/SuygetsuVAL/bimbel-laravel-app.git
cd bimbel-laravel-app
```

**2. Install dependensi PHP**
```bash
composer install
```

**3. Salin & konfigurasi file environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Konfigurasi database di `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aozoralearn
DB_USERNAME=root
DB_PASSWORD=
```

**5. Jalankan migrasi & seeder**
```bash
php artisan migrate --seed
```

**6. Buat symlink storage**
```bash
php artisan storage:link
```

**7. Jalankan server lokal**
```bash
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

> 💡 **Tip Laragon:** Taruh folder project di `C:\laragon\www\` dan akses langsung via `http://aozoralearn.test`.

---

## 🔑 Akun Demo

| Role | Email | Password |
|---|---|---|
| 👑 Super Admin | `admin@aozoralearn.id` | `admin123` |
| 👨‍🏫 Tutor Senior | `rudi@aozoralearn.id` | `tutor123` |

---

## 📁 Struktur Direktori

```
aozoralearn/
├── app/
│   ├── Exports/              # Kelas ekspor Excel (PhpSpreadsheet)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Controller portal admin
│   │   │   ├── Auth/         # AuthController (Login, Logout)
│   │   │   └── Tutor/        # Controller portal tutor
│   │   └── Middleware/       # AdminMiddleware, TutorMiddleware
│   └── Models/               # Eloquent Models
├── database/
│   ├── migrations/           # Semua migrasi tabel
│   └── seeders/              # RoleSeeder, DemoDataSeeder
├── resources/
│   └── views/
│       ├── admin/            # View portal admin
│       ├── auth/             # Halaman login
│       ├── layouts/          # Layout admin & tutor
│       └── tutor/            # View portal tutor
├── routes/
│   └── web.php               # Definisi semua route
└── tests/
    └── Feature/
        └── AozoraLearnTest.php  # Test suite
```

---

## 🧪 Testing

```bash
php artisan test
```

```
PASS  Tests\Feature\AozoraLearnTest
  ✅ test_login_page_renders
  ✅ test_root_redirects_to_login
  ✅ test_admin_can_login_and_access_all_modules   (8 assertions)
  ✅ test_tutor_can_login_and_access_all_modules   (4 assertions)

Tests: 6 passed, Assertions: 20 — Duration: ~2.2s
```

> Test menggunakan **SQLite in-memory** + `RefreshDatabase` untuk isolasi penuh.

---

## 📋 Catatan Teknis

| Topik | Detail |
|---|---|
| **Model Naming** | Menggunakan `ClassRoom` (bukan `Class`) karena `class` adalah *reserved keyword* PHP |
| **Tailwind CSS** | Via CDN — tidak memerlukan build step `npm run build` |
| **Storage File** | File materi disimpan di `storage/app/public`, diakses via `Storage::url()` |
| **Score Percent** | Accessor `score_percent` di model `Grade` = `($score / $max_score) * 100` |
| **Test Database** | Dikonfigurasi di `phpunit.xml` dengan SQLite in-memory + `$seed = true` |
| **Tema Warna** | Primary `#004ac6` (biru) untuk Portal Admin & Tutor |

---

## 🗺️ Roadmap

- [ ] 📱 Tampilan responsif mobile-first
- [ ] 🔔 Notifikasi real-time (WebSocket / Laravel Echo)
- [ ] 📲 Portal siswa (akses nilai & presensi mandiri)
- [ ] 📄 Ekspor laporan ke PDF
- [ ] 🌐 Dukungan multi-cabang bimbel
- [ ] 🔒 Dua Faktor Autentikasi (2FA)

---

## 🤝 Kontribusi

1. **Fork** repository ini
2. Buat **branch** fitur baru: `git checkout -b feature/nama-fitur`
3. **Commit** perubahan: `git commit -m 'feat: tambahkan fitur X'`
4. **Push** ke branch: `git push origin feature/nama-fitur`
5. Buat **Pull Request**

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License** — lihat file [LICENSE](LICENSE) untuk detail.

---

<div align="center">

Dibuat dengan ❤️ menggunakan **Laravel 13** & **Tailwind CSS**

⭐ **Jangan lupa beri bintang jika proyek ini bermanfaat!** ⭐

</div>
