<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SipfkIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name'     => 'Admin SIPFK',
            'email'    => 'admin@sipfk.test',
            'password' => Hash::make('password'),
        ]);

        $this->category = Category::create([
            'name'        => 'Teknologi Informasi',
            'description' => 'Fasilitas komputer, proyektor, dan jaringan internet',
        ]);
    }

    /**
     * PENGUJIAN A: Autentikasi Admin (FR-01, TC-01, TC-02)
     */
    public function test_admin_can_view_login_page(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Panel Pengelola');
    }

    public function test_admin_can_login_with_correct_credentials(): void
    {
        $response = $this->post('/login', [
            'email'    => 'admin@sipfk.test',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_cannot_login_with_wrong_password(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email'    => 'admin@sipfk.test',
            'password' => 'wrongpassword123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_cannot_login_with_unregistered_email(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email'    => 'notfound@sipfk.test',
            'password' => 'password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_admin_can_logout_and_session_is_invalidated(): void
    {
        $this->actingAs($this->admin);
        $this->assertAuthenticatedAs($this->admin);

        $response = $this->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * PENGUJIAN B: Middleware & Proteksi Akses (FR-02, TC-03)
     */
    public function test_guest_is_redirected_to_login_when_accessing_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_when_accessing_categories(): void
    {
        $response = $this->get('/admin/kategori');
        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_when_accessing_complaints(): void
    {
        $response = $this->get('/admin/pengaduan');
        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_when_accessing_reports(): void
    {
        $response = $this->get('/admin/laporan');
        $response->assertRedirect('/login');
    }

    public function test_guest_is_redirected_to_login_when_exporting_pdf(): void
    {
        $response = $this->get('/admin/laporan/pdf');
        $response->assertRedirect('/login');
    }

    public function test_public_user_can_access_home_without_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Sistem Informasi Pengaduan Fasilitas Kampus');
    }

    /**
     * PENGUJIAN C: Laporan PDF & Filter (FR-09, TC-10)
     */
    public function test_authenticated_admin_can_view_reports_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/laporan');
        $response->assertStatus(200);
        $response->assertSee('Laporan Rekapitulasi Pengaduan');
    }

    public function test_authenticated_admin_can_export_pdf(): void
    {
        $complaint = Complaint::create([
            'reporter_name' => 'Budi Pratama',
            'category_id'   => $this->category->id,
            'location'      => 'Gedung B, Lab Komputer',
            'reported_at'   => '2026-11-16',
            'description'   => 'Kabel HDMI proyektor putus, tampilan tidak muncul.',
            'status'        => 'baru',
        ]);

        FollowUp::create([
            'complaint_id' => $complaint->id,
            'admin_id'     => $this->admin->id,
            'note'         => 'Sudah dijadwalkan teknisi untuk pergantian kabel.',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/laporan/pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
        $this->assertStringStartsWith('%PDF-1.4', $response->getContent());
    }

    /**
     * PENGUJIAN D: Integrasi End-to-End Alur Penuh (FR-01 s/d FR-10)
     */
    public function test_full_system_flow_end_to_end(): void
    {
        // 1. Pelapor mengirim pengaduan secara publik
        $storeResponse = $this->post('/pengaduan', [
            'reporter_name' => 'Siti Nurhaliza',
            'category_id'   => $this->category->id,
            'location'      => 'Gedung A, Lantai 2',
            'reported_at'   => '2026-11-16',
            'description'   => 'Lampu ruangan berkedip dan sering mati mendadak.',
        ]);
        $storeResponse->assertRedirect('/');

        $this->assertDatabaseHas('complaints', [
            'reporter_name' => 'Siti Nurhaliza',
            'status'        => 'baru',
        ]);

        $complaint = Complaint::where('reporter_name', 'Siti Nurhaliza')->first();

        // 2. Admin Login
        $loginResponse = $this->post('/login', [
            'email'    => 'admin@sipfk.test',
            'password' => 'password',
        ]);
        $loginResponse->assertRedirect('/admin/dashboard');

        // 3. Admin membuka dashboard dan melihat statistik
        $dashResponse = $this->actingAs($this->admin)->get('/admin/dashboard');
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Dashboard Ringkasan Fasilitas');

        // 4. Admin memperbarui status pengaduan menjadi "diproses"
        $statusResponse = $this->actingAs($this->admin)->put("/admin/pengaduan/{$complaint->id}/status", [
            'status' => 'diproses',
        ]);
        $statusResponse->assertRedirect("/admin/pengaduan/{$complaint->id}");

        $this->assertDatabaseHas('complaints', [
            'id'     => $complaint->id,
            'status' => 'diproses',
        ]);

        // 5. Admin menambahkan catatan tindak lanjut
        $fuResponse = $this->actingAs($this->admin)->post("/admin/pengaduan/{$complaint->id}/tindak-lanjut", [
            'note' => 'Teknisi kelistrikan sedang mengganti ballast dan bohlam lampu.',
        ]);
        $fuResponse->assertRedirect("/admin/pengaduan/{$complaint->id}");

        $this->assertDatabaseHas('follow_ups', [
            'complaint_id' => $complaint->id,
            'admin_id'     => $this->admin->id,
            'note'         => 'Teknisi kelistrikan sedang mengganti ballast dan bohlam lampu.',
        ]);

        // 6. Admin mengunduh laporan PDF yang berisi data aktual dan tindak lanjut
        $pdfResponse = $this->actingAs($this->admin)->get('/admin/laporan/pdf?status=diproses');
        $pdfResponse->assertStatus(200);
        $pdfResponse->assertHeader('Content-Type', 'application/pdf');

        // 7. Admin Logout
        $logoutResponse = $this->actingAs($this->admin)->post('/logout');
        $logoutResponse->assertRedirect('/login');
        $this->assertGuest();
    }
}
