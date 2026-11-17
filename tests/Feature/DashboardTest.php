<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Nicho Admin',
            'email' => 'nicho@sipfk.test',
            'password' => Hash::make('password123'),
        ]);

        $this->category = Category::create([
            'name' => 'Sarana Gedung & Ruang Kuliah',
            'description' => 'Meja, kursi, pintu, jendela, dan ventilasi udara',
        ]);
    }

    /**
     * FR-02: Tamu tidak dapat mengakses dashboard admin.
     */
    public function test_guest_is_redirected_to_login_when_accessing_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * FR-08, AC-07: Admin yang terautentikasi dapat melihat dashboard ringkasan fasilitas.
     */
    public function test_authenticated_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard Ringkasan Fasilitas');
        $response->assertSee('Pantau metrik status pengaduan sarana dan prasarana kampus secara langsung.');
    }

    /**
     * FR-08, AC-07: Dashboard menghitung 4 metrik langsung dari database secara presisi.
     */
    public function test_dashboard_calculates_four_metrics_accurately(): void
    {
        // 2 Baru
        Complaint::create([
            'reporter_name' => 'Pelapor 1',
            'category_id' => $this->category->id,
            'location' => 'Gedung A R.101',
            'reported_at' => '2026-11-17',
            'description' => 'Kursi patah',
            'status' => Complaint::STATUS_BARU,
        ]);
        Complaint::create([
            'reporter_name' => 'Pelapor 2',
            'category_id' => $this->category->id,
            'location' => 'Gedung A R.102',
            'reported_at' => '2026-11-17',
            'description' => 'Pintu macet',
            'status' => Complaint::STATUS_BARU,
        ]);

        // 3 Diproses
        for ($i = 1; $i <= 3; $i++) {
            Complaint::create([
                'reporter_name' => "Pelapor Proses {$i}",
                'category_id' => $this->category->id,
                'location' => "Gedung B R.20{$i}",
                'reported_at' => '2026-11-16',
                'description' => "Kerusakan dalam penanganan {$i}",
                'status' => Complaint::STATUS_DIPROSES,
            ]);
        }

        // 1 Selesai
        Complaint::create([
            'reporter_name' => 'Pelapor Tuntas',
            'category_id' => $this->category->id,
            'location' => 'Gedung C R.301',
            'reported_at' => '2026-11-15',
            'description' => 'AC sudah dibersihkan',
            'status' => Complaint::STATUS_SELESAI,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);

        // Verifikasi variabel $stats yang dikirim ke view
        $response->assertViewHas('stats', [
            'total' => 6,
            'baru' => 2,
            'diproses' => 3,
            'selesai' => 1,
        ]);
    }

    /**
     * FR-08: Dashboard menampilkan daftar 5 pengaduan terbaru beserta relasi kategorinya.
     */
    public function test_dashboard_displays_recent_complaints(): void
    {
        $complaint = Complaint::create([
            'reporter_name' => 'Mahasiswa Informatika',
            'category_id' => $this->category->id,
            'location' => 'Lab Pemrograman Web',
            'reported_at' => '2026-11-17',
            'description' => 'Proyektor mati total saat praktikum',
            'status' => Complaint::STATUS_BARU,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Mahasiswa Informatika');
        $response->assertSee('Sarana Gedung & Ruang Kuliah');
        $response->assertSee('Lab Pemrograman Web');
    }

    /**
     * Dashboard menangani kondisi basis data kosong tanpa menimbulkan galat.
     */
    public function test_dashboard_renders_empty_state_cleanly(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('stats', [
            'total' => 0,
            'baru' => 0,
            'diproses' => 0,
            'selesai' => 0,
        ]);
        $response->assertSee('Belum ada data pengaduan masuk');
    }
}
