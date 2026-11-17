<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminComplaintCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $categoryKelistrikan;

    protected Category $categoryAir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Administrator SIPFK',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('password123'),
        ]);

        $this->categoryKelistrikan = Category::create([
            'name' => 'Kelistrikan & Penerangan',
            'description' => 'Saklar, lampu, dan stop kontak',
        ]);

        $this->categoryAir = Category::create([
            'name' => 'Air & Sanitasi',
            'description' => 'Toilet dan wastafel',
        ]);
    }

    /**
     * FR-02: Pengguna tanpa autentikasi ditolak saat mengakses endpoint pengaduan admin.
     */
    public function test_guest_is_redirected_to_login_on_all_admin_complaint_routes(): void
    {
        $complaint = Complaint::create([
            'reporter_name' => 'Budi Santoso',
            'category_id' => $this->categoryKelistrikan->id,
            'location' => 'Gedung A Ruang 101',
            'reported_at' => '2026-11-17',
            'description' => 'Lampu putus',
            'status' => 'baru',
        ]);

        $this->get('/admin/pengaduan')->assertRedirect('/login');
        $this->post('/admin/pengaduan', ['reporter_name' => 'Test'])->assertRedirect('/login');
        $this->get("/admin/pengaduan/{$complaint->id}")->assertRedirect('/login');
        $this->put("/admin/pengaduan/{$complaint->id}", ['reporter_name' => 'Test'])->assertRedirect('/login');
        $this->put("/admin/pengaduan/{$complaint->id}/status", ['status' => 'diproses'])->assertRedirect('/login');
        $this->delete("/admin/pengaduan/{$complaint->id}")->assertRedirect('/login');
    }

    /**
     * FR-05: Admin dapat melihat daftar pengaduan serta melakukan pencarian dan filter status & kategori.
     */
    public function test_admin_can_view_complaints_and_filter_by_status_category_or_search(): void
    {
        $c1 = Complaint::create([
            'reporter_name' => 'Andi Wijaya',
            'category_id' => $this->categoryKelistrikan->id,
            'location' => 'Laboratorium Komputer 1',
            'reported_at' => '2026-11-17',
            'description' => 'Stop kontak longgar',
            'status' => 'baru',
        ]);

        $c2 = Complaint::create([
            'reporter_name' => 'Citra Kirana',
            'category_id' => $this->categoryAir->id,
            'location' => 'Toilet Lantai 2',
            'reported_at' => '2026-11-16',
            'description' => 'Kran wastafel macet',
            'status' => 'selesai',
        ]);

        // 1. Tampilan awal tanpa filter
        $response = $this->actingAs($this->admin)->get('/admin/pengaduan');
        $response->assertStatus(200);
        $response->assertSee('Andi Wijaya');
        $response->assertSee('Citra Kirana');

        // 2. Filter Status = 'baru'
        $responseStatus = $this->actingAs($this->admin)->get('/admin/pengaduan?status=baru');
        $responseStatus->assertStatus(200);
        $responseStatus->assertSee('Andi Wijaya');
        $responseStatus->assertDontSee('Citra Kirana');

        // 3. Filter Kategori = Air & Sanitasi
        $responseCat = $this->actingAs($this->admin)->get("/admin/pengaduan?category_id={$this->categoryAir->id}");
        $responseCat->assertStatus(200);
        $responseCat->assertSee('Citra Kirana');
        $responseCat->assertDontSee('Andi Wijaya');

        // 4. Pencarian kata kunci lokasi
        $responseSearch = $this->actingAs($this->admin)->get('/admin/pengaduan?search=Laboratorium');
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('Andi Wijaya');
        $responseSearch->assertDontSee('Citra Kirana');
    }

    /**
     * FR-05: Admin dapat menambahkan pengaduan baru melalui panel admin.
     */
    public function test_admin_can_create_new_complaint(): void
    {
        $payload = [
            'reporter_name' => 'Dosen Hendra',
            'category_id' => $this->categoryKelistrikan->id,
            'location' => 'Gedung C Ruang 205',
            'reported_at' => '2026-11-17',
            'description' => 'Saklar lampu utama rusak dan tidak dapat dimatikan.',
            'status' => 'diproses',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/pengaduan', $payload);

        $response->assertRedirect('/admin/pengaduan');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'reporter_name' => 'Dosen Hendra',
            'category_id' => $this->categoryKelistrikan->id,
            'location' => 'Gedung C Ruang 205',
            'status' => 'diproses',
        ]);
    }

    /**
     * FR-10, NFR-04: Validasi sisi server menolak pembuatan pengaduan admin jika data tidak lengkap.
     */
    public function test_admin_cannot_create_complaint_with_missing_fields(): void
    {
        $response = $this->actingAs($this->admin)
            ->from('/admin/pengaduan')
            ->post('/admin/pengaduan', []);

        $response->assertRedirect('/admin/pengaduan');
        $response->assertSessionHasErrors([
            'reporter_name',
            'category_id',
            'location',
            'reported_at',
            'description',
        ]);

        $this->assertDatabaseCount('complaints', 0);
    }

    /**
     * FR-05: Admin dapat melihat detail pengaduan fasilitas.
     */
    public function test_admin_can_view_complaint_detail(): void
    {
        $complaint = Complaint::create([
            'reporter_name' => 'Rahmat Hidayat',
            'category_id' => $this->categoryAir->id,
            'location' => 'Gedung Perpustakaan Lantai 1',
            'reported_at' => '2026-11-17',
            'description' => 'Pipa saluran pembuangan bocor di dekat rak buku.',
            'status' => 'baru',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/pengaduan/{$complaint->id}");

        $response->assertStatus(200);
        $response->assertSee('Rahmat Hidayat');
        $response->assertSee('Gedung Perpustakaan Lantai 1');
        $response->assertSee('Pipa saluran pembuangan bocor');
    }

    /**
     * FR-05: Admin dapat memperbarui (edit/update) data pengaduan fasilitas.
     */
    public function test_admin_can_update_complaint(): void
    {
        $complaint = Complaint::create([
            'reporter_name' => 'Rina Sugiarto',
            'category_id' => $this->categoryKelistrikan->id,
            'location' => 'Gedung D Lantai 2',
            'reported_at' => '2026-11-15',
            'description' => 'Lampu koridor padam.',
            'status' => 'baru',
        ]);

        $updatePayload = [
            'reporter_name' => 'Rina Sugiarto (Staff TU)',
            'category_id' => $this->categoryKelistrikan->id,
            'location' => 'Gedung D Lantai 2 Depan Ruang Arsip',
            'reported_at' => '2026-11-15',
            'description' => 'Tiga lampu koridor padam dan saklar rusak.',
            'status' => 'diproses',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/pengaduan/{$complaint->id}", $updatePayload);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'reporter_name' => 'Rina Sugiarto (Staff TU)',
            'location' => 'Gedung D Lantai 2 Depan Ruang Arsip',
            'status' => 'diproses',
        ]);
    }

    /**
     * FR-06, BR-03: Admin dapat memperbarui status penanganan secara independen.
     */
    public function test_admin_can_update_status_only(): void
    {
        $complaint = Complaint::create([
            'reporter_name' => 'Ahmad Dani',
            'category_id' => $this->categoryAir->id,
            'location' => 'Gedung B Lantai 1',
            'reported_at' => '2026-11-17',
            'description' => 'Air wastafel meluap.',
            'status' => 'baru',
        ]);

        // Status valid 'selesai'
        $response = $this->actingAs($this->admin)->put("/admin/pengaduan/{$complaint->id}/status", [
            'status' => 'selesai',
        ]);
        $response->assertRedirect("/admin/pengaduan/{$complaint->id}");
        $response->assertSessionHas('success');
        $this->assertSame('selesai', $complaint->fresh()->status);

        // Status tidak valid ditolak (BR-03)
        $invalidResponse = $this->actingAs($this->admin)->put("/admin/pengaduan/{$complaint->id}/status", [
            'status' => 'status_palsu',
        ]);
        $invalidResponse->assertSessionHasErrors('status');
    }

    /**
     * FR-05: Admin dapat menghapus data pengaduan.
     */
    public function test_admin_can_delete_complaint(): void
    {
        $complaint = Complaint::create([
            'reporter_name' => 'Data Hapus',
            'category_id' => $this->categoryKelistrikan->id,
            'location' => 'Gedung F',
            'reported_at' => '2026-11-17',
            'description' => 'Akan dihapus oleh admin.',
            'status' => 'baru',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/pengaduan/{$complaint->id}");

        $response->assertRedirect('/admin/pengaduan');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('complaints', [
            'id' => $complaint->id,
        ]);
    }
}
