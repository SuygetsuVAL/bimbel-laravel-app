<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\Schedule;
use App\Models\Payment;
use App\Models\Material;
use App\Models\Grade;
use App\Models\AppNotification;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Beberapa Mata Pelajaran
        $math = Subject::firstOrCreate(['name' => 'Matematika'], ['code' => 'MTK']);
        $eng  = Subject::firstOrCreate(['name' => 'Bahasa Inggris'], ['code' => 'BIG']);
        $jap  = Subject::firstOrCreate(['name' => 'Bahasa Jepang'], ['code' => 'JPN']);

        // Ambil Pak Rudi (Tutor dari RoleSeeder)
        $rudiUser = User::where('email', 'rudi@aozoralearn.id')->first();
        $rudiTutor = Tutor::where('user_id', $rudiUser->id)->first();

        // 2. Buat Kelas
        $classA = ClassRoom::firstOrCreate(
            ['name' => 'Kelas Matematika Dasar'],
            [
                'level'        => 'X SMA',
                'subject_id'   => $math->id,
                'tutor_id'     => $rudiTutor->id,
                'max_students' => 20,
                'is_active'    => true
            ]
        );

        $classB = ClassRoom::firstOrCreate(
            ['name' => 'Kelas English Conversation'],
            [
                'level'        => 'XI SMA',
                'subject_id'   => $eng->id,
                'tutor_id'     => $rudiTutor->id,
                'max_students' => 15,
                'is_active'    => true
            ]
        );

        // 3. Buat Jadwal
        Schedule::firstOrCreate(
            ['class_id' => $classA->id, 'day_of_week' => 'Senin', 'start_time' => '08:00:00', 'end_time' => '09:30:00'],
            ['room' => 'Ruangan A', 'is_active' => true]
        );

        Schedule::firstOrCreate(
            ['class_id' => $classB->id, 'day_of_week' => 'Selasa', 'start_time' => '10:00:00', 'end_time' => '11:30:00'],
            ['room' => 'Ruangan B', 'is_active' => true]
        );

        // 4. Buat Siswa
        $siswaData = [
            [
                'name'  => 'Andi Wijaya',
                'email' => 'andi@student.id',
                'nis'   => 'NIS1001',
                'class' => $classA->id
            ],
            [
                'name'  => 'Budi Santoso',
                'email' => 'budi@student.id',
                'nis'   => 'NIS1002',
                'class' => $classA->id
            ],
            [
                'name'  => 'Citra Lestari',
                'email' => 'citra@student.id',
                'nis'   => 'NIS1003',
                'class' => $classB->id
            ],
        ];

        foreach ($siswaData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('siswa123'),
                    'phone'    => '0812' . rand(111111, 999999),
                    'is_active' => true
                ]
            );
            $user->assignRole('tutor'); // Asumsi siswa bisa login jika diperlukan, atau buat role 'student'

            Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nis'          => $data['nis'],
                    'class_id'     => $data['class'],
                    'parent_name'  => 'Orang Tua ' . $data['name'],
                    'parent_phone' => '0852' . rand(111111, 999999),
                    'parent_email' => 'parent_' . $data['email'],
                    'address'      => 'Jl. Sudirman No. ' . rand(1, 100) . ', Bengkalis',
                    'birth_date'   => '2010-05-12',
                    'join_date'    => now()->toDateString(),
                    'is_active'    => true
                ]
            );
        }

        // 5. Buat data Pembayaran untuk siswa kelas A
        $students = Student::all();
        foreach ($students as $student) {
            Payment::firstOrCreate(
                ['student_id' => $student->id, 'period' => '2026-06'],
                [
                    'amount'      => 300000,
                    'method'      => 'bca',
                    'status'      => 'lunas',
                    'paid_at'     => now()->subDays(5),
                    'verified_by' => User::where('email', 'admin@aozoralearn.id')->first()->id
                ]
            );

            Payment::firstOrCreate(
                ['student_id' => $student->id, 'period' => '2026-07'],
                [
                    'amount' => 300000,
                    'status' => 'belum_lunas'
                ]
            );
        }

        // 6. Buat Materi
        Material::firstOrCreate(
            ['title' => 'Pengenalan Logaritma Dasar'],
            [
                'class_id'     => $classA->id,
                'tutor_id'     => $rudiTutor->id,
                'description'  => 'Materi pertemuan 1 tentang konsep dasar Logaritma.',
                'file_type'    => 'pdf',
                'is_published' => true
            ]
        );

        // 7. Buat Pengumuman Default
        AppNotification::firstOrCreate(
            ['title' => 'Pembukaan Kelas Semester Baru'],
            [
                'message'     => 'Seluruh kelas bimbingan belajar tatap muka akan dimulai secara serentak pada pekan depan.',
                'target_role' => 'all',
                'sent_by'     => User::where('email', 'admin@aozoralearn.id')->first()->id
            ]
        );
    }
}
