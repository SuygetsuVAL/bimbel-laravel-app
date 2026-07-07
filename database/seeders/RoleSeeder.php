<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Tutor;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache Spatie agar tidak ada konflik
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Buat Roles ────────────────────────────────────────────────────────
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $tutorRole = Role::firstOrCreate(['name' => 'tutor']);

        // ── Buat Admin Default (Mbak Dina) ────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@aozoralearn.id'],
            [
                'name'     => 'Mbak Dina',
                'password' => bcrypt('admin123'),
                'phone'    => '08123456789',
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // ── Buat Tutor Default (Pak Rudi) ─────────────────────────────────────
        $tutorUser = User::firstOrCreate(
            ['email' => 'rudi@aozoralearn.id'],
            [
                'name'      => 'Pak Rudi',
                'password'  => bcrypt('tutor123'),
                'phone'     => '08567891234',
                'is_active' => true,
            ]
        );
        $tutorUser->assignRole('tutor');

        // Buat record di tabel tutors
        Tutor::firstOrCreate(
            ['user_id' => $tutorUser->id],
            [
                'nip'            => 'NIP001',
                'specialization' => 'Matematika',
                'education'      => 'S1 Pendidikan Matematika',
                'join_date'      => now()->toDateString(),
                'is_active'      => true,
            ]
        );

        $this->command->info('✅ Roles dan akun default berhasil dibuat.');
        $this->command->info('   Admin  → admin@aozoralearn.id / admin123');
        $this->command->info('   Tutor  → rudi@aozoralearn.id  / tutor123');
    }
}
