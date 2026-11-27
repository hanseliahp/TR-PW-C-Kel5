<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicComplaintTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Kelistrikan',
            'description' => 'Kerusakan stop kontak, lampu, dan kabel',
        ]);
    }

    /**
     * FR-03: Pelapor dapat membuka formulir pengaduan secara publik tanpa login.
     */
    public function test_public_user_can_view_complaint_form_without_login(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Formulir Pengaduan Fasilitas Kampus');
        $response->assertSee('Kelistrikan');

        $aliasResponse = $this->get('/pengaduan/buat');
        $aliasResponse->assertStatus(200);
    }

    /**
     * FR-03, BR-04, BR-07: Pelapor mengirimkan pengaduan lengkap dengan lokasi teks manual,
     * dan otomatis tersimpan dengan status 'baru'.
     */
    public function test_public_user_can_submit_complaint_with_manual_location_and_default_new_status(): void
    {
        $payload = [
            'reporter_name' => 'Ahmad Dahlan',
            'category_id' => $this->category->id,
            'location' => 'Gedung Rektorat Lantai 3 Ruang 301', // BR-07: Teks manual bukan GPS
            'reported_at' => '2026-11-17',
            'description' => 'Stop kontak di sebelah proyektor mengeluarkan percikan api saat dicolokkan.',
        ];

        $response = $this->post('/pengaduan', $payload);

        $response->assertRedirect('/');
        $response->assertSessionHas('success');

        $complaint = Complaint::where('reporter_name', 'Ahmad Dahlan')->firstOrFail();
        $this->assertSame($this->category->id, $complaint->category_id);
        $this->assertSame('Gedung Rektorat Lantai 3 Ruang 301', $complaint->location);
        $this->assertSame('2026-11-17', $complaint->reported_at->format('Y-m-d'));
        $this->assertSame('Stop kontak di sebelah proyektor mengeluarkan percikan api saat dicolokkan.', $complaint->description);
        $this->assertSame(Complaint::STATUS_BARU, $complaint->status); // BR-04: status otomatis 'baru'
    }

    /**
     * FR-10, BR-02: Validasi input wajib menolak request kosong dan menghasilkan error session.
     */
    public function test_complaint_submission_fails_when_required_fields_are_missing(): void
    {
        $response = $this->from('/')->post('/pengaduan', []);

        $response->assertRedirect('/');
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
     * BR-01: Kategori pengaduan harus terdaftar di database.
     */
    public function test_complaint_submission_fails_with_invalid_category(): void
    {
        $response = $this->from('/')->post('/pengaduan', [
            'reporter_name' => 'Budi Santoso',
            'category_id' => 9999, // ID tidak ada
            'location' => 'Gedung B Lantai 1',
            'reported_at' => '2026-11-17',
            'description' => 'Pintu kamar mandi rusak.',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseCount('complaints', 0);
    }
}
