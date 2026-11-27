<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Sarpras',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('secret123'),
        ]);
    }

    /**
     * FR-02: Tamu (belum login) tidak boleh mengakses endpoint CRUD kategori.
     */
    public function test_guest_cannot_access_category_crud_routes(): void
    {
        $category = Category::create([
            'name' => 'Kelistrikan',
            'description' => 'Fasilitas listrik',
        ]);

        $this->get('/admin/kategori')->assertRedirect('/login');
        $this->post('/admin/kategori', ['name' => 'Baru'])->assertRedirect('/login');
        $this->put("/admin/kategori/{$category->id}", ['name' => 'Ubah'])->assertRedirect('/login');
        $this->delete("/admin/kategori/{$category->id}")->assertRedirect('/login');
    }

    /**
     * FR-04: Admin dapat melihat daftar seluruh kategori beserta jumlah pengaduannya.
     */
    public function test_admin_can_view_category_list_with_complaints_count(): void
    {
        $cat1 = Category::create([
            'name' => 'Kelistrikan & Penerangan',
            'description' => 'Lampu, stop kontak, trafo',
        ]);

        $cat2 = Category::create([
            'name' => 'Air Bersih & Sanitasi',
            'description' => 'Wastafel, toilet, kran',
        ]);

        Complaint::create([
            'reporter_name' => 'Mahasiswa A',
            'category_id' => $cat1->id,
            'location' => 'Gedung A Lantai 1',
            'reported_at' => '2026-11-17',
            'description' => 'Lampu koridor mati.',
            'status' => 'baru',
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/kategori');

        $response->assertStatus(200);
        $response->assertSee('Kelistrikan &amp; Penerangan', false);
        $response->assertSee('Air Bersih &amp; Sanitasi', false);
        $response->assertSee('1 Laporan');
        $response->assertSee('0 Laporan');
    }

    /**
     * FR-04: Admin dapat menambah kategori fasilitas baru.
     */
    public function test_admin_can_create_new_category(): void
    {
        $payload = [
            'name' => 'Tata Udara & AC',
            'description' => 'Unit pendingin ruangan, exhaust fan di ruang kelas dan laboratorium',
        ];

        $response = $this->actingAs($this->admin)->post('/admin/kategori', $payload);

        $response->assertRedirect('/admin/kategori');
        $response->assertSessionHas('success', 'Kategori fasilitas berhasil ditambahkan.');

        $this->assertDatabaseHas('categories', [
            'name' => 'Tata Udara & AC',
            'description' => 'Unit pendingin ruangan, exhaust fan di ruang kelas dan laboratorium',
        ]);
    }

    /**
     * FR-10: Validasi pembuatan kategori (nama wajib dan harus unik).
     */
    public function test_category_creation_fails_when_name_is_missing_or_duplicate(): void
    {
        Category::create([
            'name' => 'Jaringan Internet',
            'description' => 'Access point dan router',
        ]);

        // 1. Nama kosong
        $responseEmpty = $this->actingAs($this->admin)->from('/admin/kategori')->post('/admin/kategori', [
            'name' => '',
            'description' => 'Deskripsi saja',
        ]);
        $responseEmpty->assertRedirect('/admin/kategori');
        $responseEmpty->assertSessionHasErrors('name');

        // 2. Nama duplikat
        $responseDuplicate = $this->actingAs($this->admin)->from('/admin/kategori')->post('/admin/kategori', [
            'name' => 'Jaringan Internet',
            'description' => 'Deskripsi baru',
        ]);
        $responseDuplicate->assertRedirect('/admin/kategori');
        $responseDuplicate->assertSessionHasErrors('name');
    }

    /**
     * FR-04: Admin dapat memperbarui nama dan deskripsi kategori yang sudah ada.
     */
    public function test_admin_can_update_existing_category(): void
    {
        $category = Category::create([
            'name' => 'Perabot Kelas',
            'description' => 'Meja dan kursi',
        ]);

        $updatePayload = [
            'name' => 'Perabot & Meubelair Ruang Kuliah',
            'description' => 'Meja kuliah, kursi lipat, papan tulis, dan podium dosen',
        ];

        $response = $this->actingAs($this->admin)->put("/admin/kategori/{$category->id}", $updatePayload);

        $response->assertRedirect('/admin/kategori');
        $response->assertSessionHas('success', 'Kategori fasilitas berhasil diperbarui.');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Perabot & Meubelair Ruang Kuliah',
            'description' => 'Meja kuliah, kursi lipat, papan tulis, dan podium dosen',
        ]);
    }

    /**
     * FR-04: Admin dapat menghapus kategori yang belum/tidak memiliki pengaduan.
     */
    public function test_admin_can_delete_category_without_complaints(): void
    {
        $category = Category::create([
            'name' => 'Taman & Lanskap',
            'description' => 'Penataan tanaman dan penerangan taman',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/kategori/{$category->id}");

        $response->assertRedirect('/admin/kategori');
        $response->assertSessionHas('success', 'Kategori berhasil dihapus.');

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * BR-08 & TC-06: Kategori yang masih digunakan oleh data pengaduan TIDAK BOLEH dihapus.
     * Sistem harus menolak dengan pesan error yang jelas dan data kategori tetap utuh.
     */
    public function test_admin_cannot_delete_category_that_has_associated_complaints(): void
    {
        $category = Category::create([
            'name' => 'LCD Proyektor & Audio',
            'description' => 'Peralatan multimedia ruang kuliah',
        ]);

        // Kaitkan satu pengaduan ke kategori ini
        Complaint::create([
            'reporter_name' => 'Dosen Budi',
            'category_id' => $category->id,
            'location' => 'Ruang Kuliah 302',
            'reported_at' => '2026-11-17',
            'description' => 'Layar proyektor buram dan bergetar.',
            'status' => 'baru',
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/kategori/{$category->id}");

        $response->assertRedirect('/admin/kategori');
        $response->assertSessionHas(
            'error',
            'Kategori tidak dapat dihapus karena masih digunakan oleh pengaduan (Aturan BR-08).'
        );

        // Pastikan kategori MASIH ADA di database (tidak terhapus)
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'LCD Proyektor & Audio',
        ]);
    }
}
