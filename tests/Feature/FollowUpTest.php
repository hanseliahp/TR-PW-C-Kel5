<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\FollowUp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class FollowUpTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin1;

    protected User $admin2;

    protected Category $category;

    protected Complaint $complaint;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin1 = User::create([
            'name' => 'Nicho Administrator',
            'email' => 'nicho@kampus.ac.id',
            'password' => Hash::make('password123'),
        ]);

        $this->admin2 = User::create([
            'name' => 'Kenzie Koordinator',
            'email' => 'kenzie@kampus.ac.id',
            'password' => Hash::make('password123'),
        ]);

        $this->category = Category::create([
            'name' => 'Kelistrikan & Pendingin Ruangan',
            'description' => 'Instalasi listrik, stop kontak, kabel, dan AC',
        ]);

        $this->complaint = Complaint::create([
            'reporter_name' => 'Budi Mahasiswa',
            'category_id' => $this->category->id,
            'location' => 'Gedung C Lantai 3 Ruang 304',
            'reported_at' => '2026-11-17',
            'description' => 'AC mengeluarkan suara bising dan tidak dingin saat perkuliahan.',
            'status' => Complaint::STATUS_BARU,
        ]);
    }

    /**
     * FR-02: Tamu dicegat saat mencoba menambahkan catatan tindak lanjut.
     */
    public function test_guest_is_redirected_to_login_when_adding_follow_up(): void
    {
        $response = $this->post("/admin/pengaduan/{$this->complaint->id}/tindak-lanjut", [
            'note' => 'Catatan tanpa login',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseCount('follow_ups', 0);
    }

    /**
     * FR-07, BR-05: Admin dapat menambahkan catatan tindak lanjut yang otomatis terikat ke Auth::id().
     */
    public function test_authenticated_admin_can_store_follow_up_note(): void
    {
        $noteText = 'Teknisi pendingin telah mengecek freon dan filter AC di Ruang 304.';

        $response = $this->actingAs($this->admin1)->post(
            "/admin/pengaduan/{$this->complaint->id}/tindak-lanjut",
            ['note' => $noteText]
        );

        $response->assertRedirect("/admin/pengaduan/{$this->complaint->id}");
        $response->assertSessionHas('success', 'Catatan tindak lanjut berhasil ditambahkan.');

        $this->assertDatabaseHas('follow_ups', [
            'complaint_id' => $this->complaint->id,
            'admin_id' => $this->admin1->id,
            'note' => $noteText,
        ]);
    }

    /**
     * BR-05: Catatan tindak lanjut secara akurat merekam ID admin yang sedang login.
     */
    public function test_follow_up_accurately_records_different_admin_identities(): void
    {
        // Admin 1 mencatat tindakan awal
        $this->actingAs($this->admin1)->post(
            "/admin/pengaduan/{$this->complaint->id}/tindak-lanjut",
            ['note' => 'Laporan diverifikasi oleh Nicho. Surat tugas teknisi diterbitkan.']
        );

        // Admin 2 mencatat tindakan lanjutan
        $this->actingAs($this->admin2)->post(
            "/admin/pengaduan/{$this->complaint->id}/tindak-lanjut",
            ['note' => 'Kenzie mengonfirmasi suku cadang kompresor telah tiba dari vendor.']
        );

        $this->assertDatabaseHas('follow_ups', [
            'complaint_id' => $this->complaint->id,
            'admin_id' => $this->admin1->id,
        ]);

        $this->assertDatabaseHas('follow_ups', [
            'complaint_id' => $this->complaint->id,
            'admin_id' => $this->admin2->id,
        ]);
    }

    /**
     * BR-06: Satu pengaduan dapat menampung multi-catatan tindak lanjut (riwayat berkala).
     */
    public function test_complaint_supports_multiple_follow_up_notes(): void
    {
        $notes = [
            '1. Laporan diterima, teknisi dijadwalkan inspeksi.',
            '2. Teknisi tiba di lokasi, memeriksa kompresor AC.',
            '3. Penggantian kapasitor selesai, AC kembali dingin normal.',
        ];

        foreach ($notes as $note) {
            $this->actingAs($this->admin1)->post(
                "/admin/pengaduan/{$this->complaint->id}/tindak-lanjut",
                ['note' => $note]
            );
        }

        $this->assertDatabaseCount('follow_ups', 3);
        $this->assertCount(3, $this->complaint->fresh()->followUps);
    }

    /**
     * FR-10, NFR-04: Validasi wajib memastikan catatan tindak lanjut tidak boleh kosong.
     */
    public function test_follow_up_requires_note_and_rejects_empty_input(): void
    {
        $response = $this->actingAs($this->admin1)->from("/admin/pengaduan/{$this->complaint->id}")->post(
            "/admin/pengaduan/{$this->complaint->id}/tindak-lanjut",
            ['note' => '']
        );

        $response->assertRedirect("/admin/pengaduan/{$this->complaint->id}");
        $response->assertSessionHasErrors(['note']);
        $this->assertDatabaseCount('follow_ups', 0);
    }

    /**
     * FR-10: Catatan tindak lanjut dibatasi maksimal 3000 karakter.
     */
    public function test_follow_up_note_max_length_validation(): void
    {
        $tooLongNote = str_repeat('A', 3001);

        $response = $this->actingAs($this->admin1)->from("/admin/pengaduan/{$this->complaint->id}")->post(
            "/admin/pengaduan/{$this->complaint->id}/tindak-lanjut",
            ['note' => $tooLongNote]
        );

        $response->assertSessionHasErrors(['note']);
        $this->assertDatabaseCount('follow_ups', 0);
    }

    /**
     * Tampilan detail memuat informasi riwayat penanganan beserta nama admin pencatatnya.
     */
    public function test_complaint_show_page_displays_follow_up_history_and_admin_name(): void
    {
        FollowUp::create([
            'complaint_id' => $this->complaint->id,
            'admin_id' => $this->admin1->id,
            'note' => 'Pemeriksaan saklar kelistrikan utama telah rampung.',
        ]);

        $response = $this->actingAs($this->admin1)->get("/admin/pengaduan/{$this->complaint->id}");

        $response->assertStatus(200);
        $response->assertSee('Nicho Administrator');
        $response->assertSee('Pemeriksaan saklar kelistrikan utama telah rampung.');
        $response->assertSee('1 Catatan');
    }

    /**
     * Tampilan detail menampilkan pesan status kosong (empty state) yang rapi saat belum ada riwayat.
     */
    public function test_complaint_show_page_displays_empty_state_when_no_follow_ups(): void
    {
        $response = $this->actingAs($this->admin1)->get("/admin/pengaduan/{$this->complaint->id}");

        $response->assertStatus(200);
        $response->assertSee('0 Catatan');
        $response->assertSee('Belum ada catatan tindak lanjut');
    }

    /**
     * FR-06, BR-03, AC-05: Pembaruan status hanya menerima nilai 'baru', 'diproses', atau 'selesai'.
     */
    public function test_admin_can_update_status_through_all_valid_lifecycle_stages(): void
    {
        // 1. Ubah status ke 'diproses'
        $resDiproses = $this->actingAs($this->admin1)->put(
            "/admin/pengaduan/{$this->complaint->id}/status",
            ['status' => Complaint::STATUS_DIPROSES]
        );
        $resDiproses->assertRedirect("/admin/pengaduan/{$this->complaint->id}");
        $this->assertSame(Complaint::STATUS_DIPROSES, $this->complaint->fresh()->status);

        // 2. Ubah status ke 'selesai'
        $resSelesai = $this->actingAs($this->admin1)->put(
            "/admin/pengaduan/{$this->complaint->id}/status",
            ['status' => Complaint::STATUS_SELESAI]
        );
        $resSelesai->assertRedirect("/admin/pengaduan/{$this->complaint->id}");
        $this->assertSame(Complaint::STATUS_SELESAI, $this->complaint->fresh()->status);

        // 3. Ubah kembali ke 'baru' jika diperlukan koreksi
        $resBaru = $this->actingAs($this->admin1)->put(
            "/admin/pengaduan/{$this->complaint->id}/status",
            ['status' => Complaint::STATUS_BARU]
        );
        $resBaru->assertRedirect("/admin/pengaduan/{$this->complaint->id}");
        $this->assertSame(Complaint::STATUS_BARU, $this->complaint->fresh()->status);
    }

    /**
     * AC-05: Sistem menolak status selain baru, diproses, dan selesai.
     */
    public function test_system_strictly_rejects_invalid_status_values(): void
    {
        $invalidStatuses = ['pending', 'ditolak', 'dibatalkan', 'in_progress', 'done', '123', ''];

        foreach ($invalidStatuses as $invalidStatus) {
            $response = $this->actingAs($this->admin1)->put(
                "/admin/pengaduan/{$this->complaint->id}/status",
                ['status' => $invalidStatus]
            );

            $response->assertSessionHasErrors(['status']);
        }

        // Pastikan status di database tetap 'baru' (tidak berubah)
        $this->assertSame(Complaint::STATUS_BARU, $this->complaint->fresh()->status);
    }
}
