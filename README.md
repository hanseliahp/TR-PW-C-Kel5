# SIPFK — Sistem Informasi Pengaduan Fasilitas Kampus
> **Tugas Rancang Akhir Semester (TRAS) Mata Kuliah Pemrograman Web**  
> **Kelas G | Kelompok G5**

SIPFK (Sistem Informasi Pengaduan Fasilitas Kampus) adalah aplikasi web terintegrasi untuk mencatat pengaduan mengenai kerusakan fasilitas di lingkungan kampus dan memantau perkembangan tindak lanjut perbaikannya oleh administrator kampus secara terpusat, transparan, dan akuntabel.

Aplikasi ini dikembangkan bersama oleh **Kelompok G5** dengan pembagian tanggung jawab yang jelas untuk 5 anggota tim pengembang.

---

## 1. PEMBAGIAN PERAN & TANGGUNG JAWAB TIM (KELOMPOK G5)

Sesuai dokumen Product Requirements Document (PRD), proyek ini diselesaikan melalui kolaborasi 5 anggota:

| Anggota | Tanggung Jawab Utama | Modul / Fitur yang Dikerjakan |
|---|---|---|
| **Anggota 1** | **Analisis Database & Fondasi Backend** | ERD, migrasi tabel (`users`, `categories`, `complaints`, `follow_ups`), model Eloquent, relasi foreign key, seeder database, dan konfigurasi lingkungan. |
| **Anggota 2** | **CRUD Kategori & Pengaduan** | Formulir pengaduan publik pelapor, validasi data sisi server (FR-10), manajemen kategori dengan proteksi relasi (BR-08), serta daftar aduan. |
| **Anggota 3** | **Alur Status, Tindak Lanjut & Dashboard** | Siklus hidup status aduan (`baru` $\rightarrow$ `diproses` $\rightarrow$ `selesai`), form pencatatan tindak lanjut (*follow-up*) admin terikat `Auth::id()`, linimasa histori penanganan, serta logika 4 metrik dashboard (FR-08, AC-07). |
| **Anggota 4** | **Antarmuka Pengguna Blade & Bootstrap** | Desain layout terpadu (`layouts/admin`, `layouts/public`, `layouts/auth`), navbar dan sidebar offcanvas responsif, 4 kartu ringkasan dashboard (FR-08), badge status semantik. |
| **Anggota 5** | **Autentikasi, Middleware, Laporan PDF, Testing & Demo** | Sistem login/logout admin dengan session regeneration, proteksi middleware `auth` pada rute admin, generator laporan PDF resmi (FR-09), automated integration testing, serta dokumentasi TRAS. |

---

## 2. TEKNOLOGI YANG DIGUNAKAN

* **Framework Backend:** Laravel 12 (PHP 8.5 / 8.4)
* **Basis Data:** MySQL / MariaDB (Didukung SQLite in-memory untuk fast test runner)
* **Template Engine:** Laravel Blade
* **Styling & Antarmuka:** Bootstrap 5.3 & Bootstrap Icons 1.11
* **Document Generator:** Clean PDF 1.4 Native Generator & DomPDF Driver
* **Pengujian Otomatis:** Pest / PHPUnit Test Suite

---

## 3. RUANG LINGKUP & FITUR APLIKASI

1. **Fitur Pelapor (Publik — Tanpa Perlu Login):**
   * Mengisi formulir aduan fasilitas kampus (nama pelapor, kategori fasilitas, lokasi kerusakan, tanggal kejadian, deskripsi masalah).
   * Validasi data sisi server dengan umpan balik pesan kesalahan yang mudah dipahami.
   * Status aduan baru otomatis tercatat sebagai `baru`.
2. **Fitur Panel Administrator Kampus (Wajib Login):**
   * **Autentikasi Aman:** Login dan logout dengan password hash Bcrypt serta proteksi dari serangan *Session Fixation*.
   * **Proteksi Akses (Middleware):** Seluruh halaman pengelolaan dibatasi hanya untuk admin yang sah.
   * **Dashboard Statistik:** Menampilkan 4 kartu ringkasan (Total Pengaduan, Status Baru, Diproses, Selesai) dan daftar 5 pengaduan fasilitas terbaru.
   * **Manajemen Kategori:** Tambah, ubah, dan hapus kategori sarana/prasarana dengan proteksi relasi aduan.
   * **Manajemen Pengaduan:** Pencarian teks bebas, penyaringan berdasarkan status dan kategori, serta paginasi data.
   * **Detail & Tindak Lanjut:** Mengubah status aduan secara bertahap dan menambahkan catatan penanganan teknisi kampus.
   * **Laporan Rekapitulasi & PDF:** Menyaring data pengaduan berdasarkan rentang tanggal dan status, serta mengekspor dokumen resmi dalam format **PDF**.

---

## 4. PERSYARATAN SISTEM

* PHP `>= 8.4` (Disarankan PHP 8.4 atau 8.5 dengan ekstensi `pdo_mysql`, `pdo_sqlite`, `sqlite3`, `curl`, `mbstring`, `zip`, dan `zlib`).
* Composer 2.x
* MySQL / MariaDB Server (misalnya via Laragon atau XAMPP)
* Web Browser modern (Google Chrome, Microsoft Edge, Mozilla Firefox)

---

## 5. PANDUAN MENJALANKAN PROYEK SECARA LOKAL

### Langkah 1: Kloning Repositori
```bash
git clone https://github.com/Jonathan5124/TR-PW-C-Kel5.git
cd TR-PW-C-Kel5
```

### Langkah 2: Instalasi Dependensi Proyek
```bash
composer install
```

### Langkah 3: Konfigurasi File Lingkungan (`.env`)
Salin file template konfigurasi dan buat application key:
```bash
cp .env.example .env
php artisan key:generate
```

Buka file `.env` dan pastikan konfigurasi database sesuai dengan server MySQL lokal Anda:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pengaduanfasilitas_db
DB_USERNAME=root
DB_PASSWORD=
```
*(Pastikan Anda telah membuat basis data bernama `pengaduanfasilitas_db` pada server MySQL Anda)*

### Langkah 4: Migrasi Database & Seeding Data Awal
Jalankan migrasi seluruh tabel dan seeder data awal (akun admin default, kategori fasilitas, dan data contoh):
```bash
php artisan migrate --seed
```

### Langkah 5: Menjalankan Server Aplikasi
```bash
php artisan serve
```
Aplikasi web dapat langsung diakses melalui browser pada alamat:
👉 **`http://localhost:8000`**

---

## 6. PETA ALAMAT AKSES DAN KREDENSIAL PENGUJIAN

| Halaman | URL Akses | Hak Akses | Keterangan & Modul Anggota |
|---|---|---|---|
| **Formulir Pengaduan Publik** | `http://localhost:8000/` | Publik | Tempat pelapor mengisi aduan kerusakan fasilitas (Anggota 2 & 4). |
| **Login Administrator** | `http://localhost:8000/login` | Tamu / Guest | Form masuk admin pengelola kampus (Anggota 4 & 5). |
| **Dashboard Admin** | `http://localhost:8000/admin/dashboard` | Administrator | Ringkasan 4 metrik kartu status pengaduan (Anggota 4). |
| **Kelola Kategori** | `http://localhost:8000/admin/kategori` | Administrator | CRUD kategori fasilitas kampus (Anggota 2). |
| **Kelola Pengaduan** | `http://localhost:8000/admin/pengaduan` | Administrator | Daftar aduan, filter, dan pagination (Anggota 2 & 3). |
| **Detail Pengaduan** | `http://localhost:8000/admin/pengaduan/{id}` | Administrator | Ubah status dan tambah riwayat tindak lanjut (Anggota 3). |
| **Laporan Rekapitulasi & PDF** | `http://localhost:8000/admin/laporan` | Administrator | Filter tanggal & unduh/cetak dokumen PDF (Anggota 5). |

### Kredensial Administrator (Hasil Seeder):
* **Email:** `admin@sipfk.test`
* **Kata Sandi:** `password`

---

## 7. CARA MENGHASILKAN LAPORAN PDF

1. Masuk ke panel administrator melalui alamat `http://localhost:8000/login`.
2. Klik menu **Laporan Pengaduan** pada sidebar navigasi.
3. Gunakan filter periode tanggal (*Dari Tanggal* s.d. *Sampai Tanggal*) atau filter status untuk menyaring data yang dibutuhkan.
4. Klik salah satu tombol aksi:
   * **Pratinjau PDF:** Membuka lembar laporan PDF di tab baru browser.
   * **Unduh File PDF:** Mengunduh berkas laporan biner PDF secara langsung.
   * **Cetak Layar:** Mencetak langsung tampilan tabel ke printer fisik.

---

## 8. MENJALANKAN AUTOMATED INTEGRATION TESTS

Seluruh fungsionalitas sistem (autentikasi, middleware, CRUD, alur tindak lanjut, laporan PDF, dan integrasi end-to-end) diuji secara otomatis:
```bash
php artisan test
```
*Hasil Uji: 16 skenario pengujian dengan 62 assertions lolos 100% tanpa error.*

---

## 9. STRUKTUR DOKUMENTASI PROYEK

Dokumentasi lengkap untuk persiapan sidang dan evaluasi TRAS tersedia pada folder `docs/`:
* [docs/DOKUMENTASI_TEKNIS.md](file:///c:/laragon/www/TR-PW-C-Kel5/docs/DOKUMENTASI_TEKNIS.md): Rincian teknis arsitektur sistem, database, alur CRUD, status follow-up, UI, dan keamanan untuk seluruh anggota tim.
* [docs/PANDUAN_DEMONSTRASI.md](file:///c:/laragon/www/TR-PW-C-Kel5/docs/PANDUAN_DEMONSTRASI.md): Skenario simulasi demo per anggota, narasi presentasi di hadapan dosen, kode sumber penting, serta prediksi pertanyaan dan jawaban.
* [docs/BUKTI_PENGUJIAN_DAN_SCREENSHOT.md](file:///c:/laragon/www/TR-PW-C-Kel5/docs/BUKTI_PENGUJIAN_DAN_SCREENSHOT.md): Matriks test case (TC-01 s/d TC-16), log uji otomatis, dan panduan screenshot antarmuka.

---

## 10. SOLUSI PERMASALAHAN UMUM (TROUBLESHOOTING)

1. **Error: `Database [pengaduanfasilitas_db] does not exist`**  
   *Buat database `pengaduanfasilitas_db` terlebih dahulu pada MySQL/MariaDB (misal via HeidiSQL atau phpMyAdmin), lalu jalankan `php artisan migrate --seed`.*
2. **Akses halaman admin selalu kembali ke `/login`**  
   *Ini adalah proteksi middleware `auth` (FR-02). Anda harus login terlebih dahulu dengan akun admin.*
3. **Peringatan ekstensi ZIP saat menjalankan Composer:**  
   *Aktifkan baris `extension=zip` pada file konfigurasi `php.ini` Anda.*
4. **Membersihkan Cache Konfigurasi:**  
   ```bash
   php artisan optimize:clear
   ```
