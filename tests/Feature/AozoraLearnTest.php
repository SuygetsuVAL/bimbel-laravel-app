<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AozoraLearnTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Jalankan seeder secara otomatis sebelum setiap test case.
     */
    protected bool $seed = true;

    /**
     * Test login page works.
     */
    public function test_login_page_renders(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    /**
     * Test redirect to login from root.
     */
    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * Test admin login and module access.
     */
    public function test_admin_can_login_and_access_all_modules(): void
    {
        // Temukan user admin dari seeder
        $admin = User::where('email', 'admin@aozoralearn.id')->first();
        $this->assertNotNull($admin, 'Admin user not found. Please run db:seed first.');

        // Login as admin
        $this->actingAs($admin);

        // Akses dashboard admin
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);

        // Akses manajemen siswa
        $response = $this->get('/admin/students');
        $response->assertStatus(200);

        // Akses manajemen tutor
        $response = $this->get('/admin/tutors');
        $response->assertStatus(200);

        // Akses manajemen kelas
        $response = $this->get('/admin/classes');
        $response->assertStatus(200);

        // Akses manajemen jadwal
        $response = $this->get('/admin/schedules');
        $response->assertStatus(200);

        // Akses keuangan
        $response = $this->get('/admin/payments');
        $response->assertStatus(200);

        // Akses laporan
        $response = $this->get('/admin/reports');
        $response->assertStatus(200);

        // Akses pengumuman
        $response = $this->get('/admin/notifications');
        $response->assertStatus(200);
    }

    /**
     * Test tutor login and module access.
     */
    public function test_tutor_can_login_and_access_all_modules(): void
    {
        // Temukan user tutor Rudi dari seeder
        $tutor = User::where('email', 'rudi@aozoralearn.id')->first();
        $this->assertNotNull($tutor, 'Tutor user Rudi not found. Please run db:seed first.');

        // Login as tutor
        $this->actingAs($tutor);

        // Akses dashboard tutor
        $response = $this->get('/tutor/dashboard');
        $response->assertStatus(200);

        // Akses presensi & kehadiran
        $response = $this->get('/tutor/attendance');
        $response->assertStatus(200);

        // Akses materi pelajaran
        $response = $this->get('/tutor/materials');
        $response->assertStatus(200);

        // Akses rekap nilai
        $response = $this->get('/tutor/grades');
        $response->assertStatus(200);
    }
}
