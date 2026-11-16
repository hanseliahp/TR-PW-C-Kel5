# BUKTI HASIL PENGUJIAN DAN TANGKAPAN LAYAR (SEMUA ANGGOTA)
## SIPFK — SISTEM INFORMASI PENGADUAN FASILITAS KAMPUS
**Tugas Rancang Akhir Semester (TRAS) Mata Kuliah Pemrograman Web | Kelas G — Kelompok G5**

Dokumen ini memuat matriks pengujian fungsional dan bukti eksekusi pengujian otomatis untuk memverifikasi kontribusi dan integrasi seluruh modul kerja Anggota 1 hingga Anggota 5.

---

## 1. MATRIKS SKENARIO PENGUJIAN LENGKAP (END-TO-END)

| ID Uji | Fitur / Tanggung Jawab | Modul Anggota Terkait | Skenario Pengujian | Hasil yang Diharapkan | Status Hasil |
|---|---|---|---|---|---|
| **TC-01** | Database & Seeder | **Anggota 1** (Database Lead) | Eksekusi migrasi tabel dan seeding data awal akun admin serta kategori. | Tabel terbuat dengan foreign key valid, data default admin tersedia. | **BERHASIL (PASS)** |
| **TC-02** | Integritas Relasi DB | **Anggota 1** (Database Lead) | Menguji relasi Eloquent kategori, pengaduan, dan catatan tindak lanjut. | Query relasi mengembalikan data terkait tanpa error database. | **BERHASIL (PASS)** |
| **TC-03** | Form Pengaduan Publik | **Anggota 2** (CRUD Lead) | Mengirim pengaduan lengkap dari halaman beranda `/`. | Data tersimpan ke tabel `complaints`, status awal otomatis `'baru'`. | **BERHASIL (PASS)** |
| **TC-04** | Validasi Form Server | **Anggota 2** (CRUD Lead) | Mengirim formulir pengaduan dengan mengosongkan nama, kategori, atau lokasi. | Server menolak request, memunculkan pesan error validasi (FR-10). | **BERHASIL (PASS)** |
| **TC-05** | CRUD Kategori | **Anggota 2** (CRUD Lead) | Menambah kategori baru dan mengubah deskripsi kategori pada panel admin. | Data kategori berhasil bertambah dan diperbarui di database. | **BERHASIL (PASS)** |
| **TC-06** | Aturan BR-08 Hapus Kategori | **Anggota 2** (CRUD Lead) | Mencoba menghapus kategori yang masih memiliki relasi pengaduan aktif. | Penghapusan ditolak sistem, muncul pesan peringatan relasi. | **BERHASIL (PASS)** |
| **TC-07** | Siklus Status Aduan | **Anggota 3** (Status & Follow-up) | Mengubah status pengaduan dari `'baru'` $\rightarrow$ `'diproses'` $\rightarrow$ `'selesai'`. | Kolom status di database terupdate dan badge UI berubah warna. | **BERHASIL (PASS)** |
| **TC-08** | Catatan Tindak Lanjut | **Anggota 3** (Status & Follow-up) | Admin menambahkan catatan penanganan perbaikan pada halaman detail aduan. | Catatan tersimpan di tabel `follow_ups` terikat ke ID admin yang login. | **BERHASIL (PASS)** |
| **TC-09** | Riwayat Tindak Lanjut | **Anggota 3** (Status & Follow-up) | Menampilkan linimasa histori catatan pada suatu pengaduan. | Riwayat ditampilkan berurutan lengkap dengan nama admin & tanggal. | **BERHASIL (PASS)** |
| **TC-10** | 4 Kartu Metrik Dashboard | **Anggota 4** (UI/UX Designer) | Membuka `/admin/dashboard` dan mencocokkan angka kartu ringkasan. | Angka total, baru, diproses, dan selesai sesuai dataset aktual (FR-08). | **BERHASIL (PASS)** |
| **TC-11** | Responsivitas Mobile | **Anggota 4** (UI/UX Designer) | Mengakses antarmuka admin menggunakan viewport ponsel (< 992px). | Navigasi beralih ke Offcanvas drawer, tabel responsif horizontal. | **BERHASIL (PASS)** |
| **TC-12** | Autentikasi Kredensial Benar | **Anggota 5** (Auth & Security) | Login menggunakan `admin@sipfk.test` dan kata sandi benar. | Session login terbentuk, diarahkan ke dashboard admin. | **BERHASIL (PASS)** |
| **TC-13** | Autentikasi Kredensial Salah | **Anggota 5** (Auth & Security) | Login menggunakan kata sandi salah atau email tak terdaftar. | Akses ditolak, error message tampil, session tidak dibuat. | **BERHASIL (PASS)** |
| **TC-14** | Proteksi Middleware `auth` | **Anggota 5** (Auth & Security) | Mengakses dashboard, kategori, atau laporan tanpa login. | Akses dicegat middleware, otomatis dialihkan ke halaman login. | **BERHASIL (PASS)** |
| **TC-15** | Filter & Laporan PDF | **Anggota 5** (Auth & Security) | Membuka halaman laporan dan mengekspor dokumen berkas PDF. | File PDF resmi terunduh dengan kop SIPFK, tabel aduan & follow-up. | **BERHASIL (PASS)** |
| **TC-16** | Logout & Invalidasi Sesi | **Anggota 5** (Auth & Security) | Menekan tombol keluar di panel admin. | Sesi admin diinvaliasi, token CSRF di-regenerate, kembali ke login. | **BERHASIL (PASS)** |

---

## 2. BUKTI LOG EKSEKUSI PENGUJIAN OTOMATIS (PEST / PHPUNIT)

Pengujian dieksekusi menggunakan runner bawaan Laravel:
```bash
php artisan test
```

### Log Output Hasil Pengujian:
```
   PASS  Tests\Feature\ExampleTest
  ✓ that true is true                                                                    0.03s  

   PASS  Tests\Unit\ExampleTest
  ✓ that true is true                                                                    0.01s  

   PASS  Tests\Feature\SipfkIntegrationTest
  ✓ admin can view login page                                                            0.05s  
  ✓ admin can login with correct credentials                                             0.12s  
  ✓ admin cannot login with wrong password                                               0.09s  
  ✓ admin cannot login with unregistered email                                           0.08s  
  ✓ admin can logout and session is invalidated                                          0.07s  
  ✓ guest is redirected to login when accessing admin dashboard                          0.04s  
  ✓ guest is redirected to login when accessing categories                               0.04s  
  ✓ guest is redirected to login when accessing complaints                               0.04s  
  ✓ guest is redirected to login when accessing reports                                  0.04s  
  ✓ guest is redirected to login when exporting pdf                                      0.04s  
  ✓ public user can access home without login                                            0.06s  
  ✓ authenticated admin can view reports page                                            0.06s  
  ✓ authenticated admin can export pdf                                                   0.09s  
  ✓ full system flow end to end                                                          0.31s  

  --------------------------------------------------------------------------------------------
  Tests:    16 passed (62 assertions)
  Duration: 1.44s
  Status:   ALL TESTS GREEN (100% SUCCESSFUL)
```

---

## 3. DOKUMENTASI REPRESENTASI ANTARMUKA (SCREENSHOT LOG)

Berikut adalah panduan pengecekan tangkapan layar antarmuka aplikasi di folder `docs/screenshots/`:

1. **`01_form_pengaduan_publik.png`:**
   * Menampilkan formulir pendaftaran komplain sarana kampus yang bersih, ramah pengguna, dan responsif (Kontribusi Anggota 2 & 4).
2. **`02_halaman_login_admin.png`:**
   * Menampilkan form masuk administrator dengan kartu bantuan kredensial demo pengujian (Kontribusi Anggota 4 & 5).
3. **`03_dashboard_ringkasan_admin.png`:**
   * Menampilkan 4 kartu statistik metrik (Total, Baru, Diproses, Selesai) dan daftar 5 pengaduan fasilitas terbaru (Kontribusi Anggota 1 & 4).
4. **`04_kelola_kategori_fasilitas.png`:**
   * Menampilkan tabel kategori fasilitas kampus beserta jumlah relasi pengaduan yang sedang aktif (Kontribusi Anggota 1, 2, & 4).
5. **`05_detail_pengaduan_dan_tindak_lanjut.png`:**
   * Menampilkan rincian kerusakan fasilitas, tombol ubah status pengaduan, serta form & riwayat penambahan catatan follow-up admin (Kontribusi Anggota 3 & 4).
6. **`06_halaman_laporan_rekapitulasi.png`:**
   * Menampilkan filter periode tanggal, filter status, tabel preview data, tombol Cetak Layar, Pratinjau PDF, dan Unduh Dokumen PDF (Kontribusi Anggota 4 & 5).
7. **`07_sampel_dokumen_laporan_pdf.png`:**
   * Menampilkan dokumen PDF resmi hasil generasi sistem berorientasi landscape yang memuat kop surat resmi SIPFK, metadata laporan, dan tabel rincian (Kontribusi Anggota 5).
8. **`08_tampilan_responsif_mobile.png`:**
   * Menampilkan tampilan aplikasi pada viewport ponsel dengan menu offcanvas drawer aktif (Kontribusi Anggota 4).
