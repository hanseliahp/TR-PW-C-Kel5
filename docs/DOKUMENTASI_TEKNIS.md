# DOKUMENTASI TEKNIS SISTEM LENGKAP
## SIPFK — SISTEM INFORMASI PENGADUAN FASILITAS KAMPUS
**Tugas Rancang Akhir Semester (TRAS) Mata Kuliah Pemrograman Web | Kelas G — Kelompok G5**

---

## 1. DESKRIPSI DAN ARSITEKTUR APLIKASI

SIPFK (Sistem Informasi Pengaduan Fasilitas Kampus) adalah aplikasi web berbasis **Laravel 12** yang dirancang untuk mendigitalkan proses pencatatan, pelacakan, penanganan, dan pelaporan kerusakan sarana dan prasarana di lingkungan kampus.

Aplikasi ini menggunakan pola arsitektur **Model-View-Controller (MVC)** yang terstruktur:
* **Models & Migrations:** Merepresentasikan entitas data (`users`, `categories`, `complaints`, `follow_ups`) dengan integritas referensial foreign key.
* **Controllers & Routes:** Mengatur alur logika bisnis, validasi input sisi server (*server-side validation*), kontrol otorisasi middleware, dan ekspor laporan PDF.
* **Views (Blade & Bootstrap 5.3):** Menyajikan antarmuka yang bersih, responsif (*mobile & desktop*), konsisten (*anti-AI-slop*), dan aksesibel.

---

## 2. PEMBAGIAN PERAN DAN TANGGUNG JAWAB SELURUH ANGGOTA (KELOMPOK G5)

Sesuai dengan dokumen PRD Bagian 13, pengerjaan proyek dibagi secara jelas ke dalam 5 anggota kelompok:

| Anggota | Peran & Tanggung Jawab Utama | Lingkup File / Kode | Hasil Kerja Nyata |
|---|---|---|---|
| **Anggota 1** | **Analisis Database & Fondasi Backend**<br>ERD, Migrasi tabel, Model Eloquent, Relasi foreign key, Konfigurasi database, dan Seeder. | `database/migrations/*`<br>`database/seeders/*`<br>`app/Models/*`<br>`.env` | 4 skema tabel terelasi, model Eloquent aktif, seeder akun admin, dan dataset awal pengaduan & kategori. |
| **Anggota 2** | **CRUD Kategori & Pengaduan**<br>Pengelolaan data kategori dan aduan, validasi input wajib, logika simpan/ubah/hapus, dan aturan relasi BR-08. | `app/Http/Controllers/CategoryController` (or Route Handlers)<br>`resources/views/admin/categories/*`<br>`resources/views/admin/complaints/*` | Manajemen kategori, daftar pengaduan dengan filter pencarian & kategori, penolakan hapus kategori berkait. |
| **Anggota 3** | **Alur Status, Tindak Lanjut & Dashboard**<br>Siklus hidup status aduan (`baru` $\rightarrow$ `diproses` $\rightarrow$ `selesai`), pencatatan follow-up admin terikat `Auth::id()`, riwayat penanganan kronologis, dan logika 4 metrik dashboard. | `app/Http/Controllers/DashboardController.php`<br>`app/Http/Controllers/FollowUpController.php`<br>`resources/views/admin/complaints/show.blade.php`<br>`tests/Feature/DashboardTest.php`<br>`tests/Feature/FollowUpTest.php` | Controller dashboard 4 metrik riil (FR-08, AC-07), controller multi follow-up (FR-07, BR-05, BR-06), validasi status ketat (FR-06, BR-03, AC-05), linimasa riwayat penanganan, 15 automated test cases. |
| **Anggota 4** | **Antarmuka Pengguna Blade & Bootstrap**<br>Desain tata letak terpadu, komponen navigasi, kartu statistik dashboard, responsivitas, dan estetika UI. | `resources/views/layouts/*`<br>`resources/views/components/*`<br>`resources/views/public/*`<br>`resources/views/admin/dashboard.blade.php`<br>`public/css/sipfk-custom.css` | Layout utama admin & publik, navbar & sidebar offcanvas responsif, 4 stat-card dashboard (FR-08), badge status. |
| **Anggota 5** | **Autentikasi, Middleware, Laporan PDF, Testing & Demo**<br>Sistem login/logout, proteksi route admin, generator PDF laporan, automated testing, dan panduan dokumentasi. | `app/Http/Controllers/AuthController.php`<br>`app/Http/Controllers/ReportController.php`<br>`app/Services/Pdf/SimplePdfReport.php`<br>`resources/views/admin/reports/*`<br>`tests/Feature/SipfkIntegrationTest.php`<br>`docs/*`, `README.md` | Login/logout aman, proteksi `auth` middleware, dokumen PDF 1.4 resmi, 16 automated tests passed, dokumentasi & panduan demo. |

---

## 3. RANCANGAN STRUKTUR DATA DAN RELASI DATABASE (ANGGOTA 1)

Database `pengaduanfasilitas_db` terdiri dari 4 tabel utama:

```
[ users ] (1) <────── (N) [ follow_ups ] (N) ──────> (1) [ complaints ] (N) ──────> (1) [ categories ]
 (Admin)                   (Tindak Lanjut)                   (Pengaduan)                     (Kategori)
```

### 3.1 Skema Tabel dan Tipe Data
1. **`users`** (Tabel Akun Administrator):
   * `id`: BIGINT (Primary Key, Auto Increment)
   * `name`: VARCHAR(255) — Nama lengkap administrator
   * `email`: VARCHAR(255) — Email unik untuk login (`admin@sipfk.test`)
   * `password`: VARCHAR(255) — Hash Bcrypt dari kata sandi
   * `timestamps`: `created_at`, `updated_at`
2. **`categories`** (Tabel Kategori Fasilitas):
   * `id`: BIGINT (Primary Key, Auto Increment)
   * `name`: VARCHAR(100) — Nama kategori fasilitas (Unique)
   * `description`: TEXT (Nullable) — Penjelasan cakupan fasilitas
   * `timestamps`: `created_at`, `updated_at`
3. **`complaints`** (Tabel Data Pengaduan):
   * `id`: BIGINT (Primary Key, Auto Increment)
   * `category_id`: BIGINT (Foreign Key ke `categories.id`, onDelete RESTRICT)
   * `reporter_name`: VARCHAR(100) — Nama pelapor fasilitas
   * `location`: VARCHAR(150) — Rincian lokasi kerusakan
   * `reported_at`: DATE — Tanggal laporan disampaikan
   * `description`: TEXT — Uraian lengkap kendala fasilitas
   * `status`: VARCHAR(20) — Status: `'baru'`, `'diproses'`, atau `'selesai'` (Default: `'baru'`)
   * `timestamps`: `created_at`, `updated_at`
4. **`follow_ups`** (Tabel Catatan Tindak Lanjut):
   * `id`: BIGINT (Primary Key, Auto Increment)
   * `complaint_id`: BIGINT (Foreign Key ke `complaints.id`, onDelete CASCADE)
   * `admin_id`: BIGINT (Foreign Key ke `users.id`, onDelete RESTRICT)
   * `note`: TEXT — Uraian tindakan perbaikan atau pengecekan
   * `timestamps`: `created_at`, `updated_at`

### 3.2 Relasi Eloquent
* `Category` $\rightarrow$ `hasMany(Complaint::class)`
* `Complaint` $\rightarrow$ `belongsTo(Category::class)`
* `Complaint` $\rightarrow$ `hasMany(FollowUp::class)`
* `FollowUp` $\rightarrow$ `belongsTo(Complaint::class)` & `belongsTo(User::class, 'admin_id')`
* `User` $\rightarrow$ `hasMany(FollowUp::class, 'admin_id')`

---

## 4. SISTEM CRUD KATEGORI DAN PENGADUAN (ANGGOTA 2)

### 4.1 Manajemen Kategori Fasilitas (`/admin/kategori`)
* **Create:** Admin menambahkan kategori baru dengan validasi nama unik dan maksimal 100 karakter.
* **Read:** Menampilkan tabel seluruh kategori dilengkapi jumlah aduan terkait (`withCount('complaints')`).
* **Update:** Memperbarui nama dan deskripsi kategori.
* **Delete (Aturan BR-08):** Sistem memeriksa `$cat->complaints()->exists()`. Jika kategori masih digunakan oleh data pengaduan yang aktif, aksi penghapusan dibatalkan dan sistem memunculkan pesan peringatan.

### 4.2 Manajemen Data Pengaduan (`/admin/pengaduan`)
* **Pencarian & Filter Multikriteria:** Fitur pencarian kata kunci nama pelapor, lokasi, atau deskripsi, serta dropdown penyaringan berdasarkan status dan kategori.
* **Pagination Terpadu:** Membatasi 10 data per halaman dengan menjaga parameter filter (`withQueryString()`).
* **Hapus Pengaduan:** Menghapus data pengaduan beserta cascade catatan tindak lanjutnya melalui modal konfirmasi aman.

---

## 5. ALUR STATUS DAN TINDAK LANJUT PENANGANAN (ANGGOTA 3)

### 5.1 Siklus Status Pengaduan (FR-06, BR-03)
Pengaduan mengikuti siklus status resmi tiga tahap:
1. **`baru`**: Status otomatis saat pelapor mengirim formulir dari halaman publik (`/pengaduan`).
2. **`diproses`**: Diubah oleh admin saat teknisi atau petugas kampus mulai menangani masalah.
3. **`selesai`**: Diubah oleh admin saat sarana/prasarana telah diperbaiki dan diverifikasi.

### 5.2 Pencatatan Catatan Tindak Lanjut (FR-07, BR-05, BR-06)
* Diimplementasikan melalui `FollowUpController::store()`.
* Admin yang sedang login dapat menginput catatan berkala pada halaman detail pengaduan (`/admin/pengaduan/{id}`).
* Setiap catatan otomatis menyimpan ID admin yang bertugas (`Auth::id()`) dan waktu pencatatan (BR-05).
* Satu pengaduan dapat menampung banyak catatan tindak lanjut (*history trail* / multi follow-up sesuai BR-06), yang ditampilkan secara urut pada bagian detail dan dicantumkan ke dalam laporan resmi.
* Dilengkapi penanganan *empty state* yang rapi saat pengaduan belum memiliki catatan penanganan.

### 5.3 Logika Perhitungan Ringkasan Dashboard (FR-08, AC-07)
* Diimplementasikan secara terstruktur melalui `DashboardController::index()`.
* Menghitung empat metrik langsung dari basis data tanpa hardcoding nilai:
  1. **Total Seluruh Pengaduan:** `Complaint::count()`
  2. **Pengaduan Status Baru:** `Complaint::where('status', Complaint::STATUS_BARU)->count()`
  3. **Pengaduan Status Diproses:** `Complaint::where('status', Complaint::STATUS_DIPROSES)->count()`
  4. **Pengaduan Status Selesai:** `Complaint::where('status', Complaint::STATUS_SELESAI)->count()`
* Menyediakan 5 data pengaduan terkini (`recentComplaints`) dengan eager loading relasi kategori (`with('category')`) untuk mengoptimalkan performa kueri dan mencegah *N+1 query problem*.

---

## 6. SISTEM ANTARMUKA, LAYOUT, DAN DASHBOARD (ANGGOTA 4)

### 6.1 Desain Layout Modular
* **`layouts/public.blade.php`:** Halaman antarmuka pelapor dengan visual ramah, formulir jelas, dan petunjuk pengisian.
* **`layouts/auth.blade.php`:** Tampilan terpusat untuk formulir login administrator.
* **`layouts/admin.blade.php`:** Panel admin terpadu dengan topbar navigasi, identitas pengguna aktif, tombol pintas, serta sidebar responsif ganda (sidebar tetap untuk desktop $\ge 992\text{px}$ dan Bootstrap Offcanvas untuk layar sentuh ponsel $< 992\text{px}$).

### 6.2 Dashboard 4 Kartu Statistik (FR-08, Section 5.1 PRD)
Dashboard admin menampilkan 4 metrik kartu ringkasan berbasis data riil database:
1. **Total Seluruh Pengaduan:** Menghitung total semua aduan yang pernah masuk.
2. **Pengaduan Status Baru:** Menghitung aduan yang belum ditangani (badge kuning/amber).
3. **Pengaduan Status Diproses:** Menghitung aduan yang sedang dalam pengerjaan (badge biru).
4. **Pengaduan Status Selesai:** Menghitung aduan yang tuntas diperbaiki (badge hijau).
Serta tabel 5 pengaduan terbaru (*Recent Complaints*) dengan pintasan cepat menuju halaman detail.

---

## 7. SISTEM KEAMANAN, MIDDLEWARE, DAN LAPORAN PDF (ANGGOTA 5)

### 7.1 Autentikasi & Session Lifecycle
* Form login divalidasi ketat via `AuthController::login()`.
* Otentikasi menggunakan fasilitas hash Bcrypt Laravel.
* `$request->session()->regenerate()` dijalankan saat login sukses untuk mencegah pembajakan sesi.
* `$request->session()->invalidate()` dan `$request->session()->regenerateToken()` dijalankan saat logout untuk membersihkan sesi dan token CSRF.

### 7.2 Proteksi Middleware (`auth` & `guest`)
* Rute publik formulir terbuka bagi siapa saja tanpa akun.
* Seluruh rute pengelolaan admin `/admin/*` diisolasi oleh middleware `auth`. Tamu yang mencoba membuka halaman admin langsung dicegat dan dialihkan ke `/login`.
* Rute `/login` dilindungi middleware `guest` agar admin yang telah masuk tidak melihat form login berulang kali.

### 7.3 Laporan Pengaduan Format Dokumen PDF (FR-09)
* Menghasilkan dokumen resmi PDF 1.4 A4 Landscape melalui `ReportController::exportPdf()` dan service class `SimplePdfReport`.
* Mendukung filter tanggal dan status.
* Dokumen menyajikan kop instansi SIPFK, metadata periode, tabel data pengaduan, riwayat catatan tindak lanjut admin, stempel penanggung jawab, dan nomor halaman dinamis.
* Menyediakan dua opsi keluaran: **Unduh PDF** (*attachment*) dan **Pratinjau PDF di Tab Browser** (*stream*).

---

## 8. STRUKTUR FILE LENGKAP PROYEK

```
TR-PW-C-Kel5/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php          # [Anggota 5] Login & Logout Admin
│   │   ├── ReportController.php        # [Anggota 5] Filter & Ekspor PDF
│   │   └── Controller.php
│   ├── Models/
│   │   ├── Category.php                # [Anggota 1] Model Kategori
│   │   ├── Complaint.php               # [Anggota 1] Model Pengaduan
│   │   ├── FollowUp.php                # [Anggota 1 & 3] Model Tindak Lanjut
│   │   └── User.php                    # [Anggota 1 & 5] Model Akun Admin
│   └── Services/
│       └── Pdf/
│           └── SimplePdfReport.php     # [Anggota 5] Clean PDF Engine
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_11_16_000001_create_categories_table.php
│   │   ├── 2026_11_16_000002_create_complaints_table.php
│   │   └── 2026_11_16_000003_create_follow_ups_table.php
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php              # Akun default admin@sipfk.test
│       ├── CategorySeeder.php          # 7 Kategori fasilitas awal
│       └── ComplaintSeeder.php         # Data dummy aduan & tindak lanjut
├── resources/views/
│   ├── admin/
│   │   ├── categories/index.blade.php  # [Anggota 2 & 4] Kelola Kategori
│   │   ├── complaints/index.blade.php  # [Anggota 2 & 4] Kelola Pengaduan
│   │   ├── complaints/show.blade.php   # [Anggota 3 & 4] Detail & Follow-up
│   │   ├── reports/index.blade.php     # [Anggota 5 & 4] Filter & Rekap Laporan
│   │   ├── reports/pdf.blade.php       # [Anggota 5] Template Dokumen PDF
│   │   └── dashboard.blade.php         # [Anggota 4] 4 Kartu Statistik
│   ├── auth/
│   │   └── login.blade.php             # [Anggota 5 & 4] Form Login Admin
│   ├── public/
│   │   └── complaint-form.blade.php    # [Anggota 2 & 4] Form Pengaduan Publik
│   └── layouts/
│       ├── admin.blade.php             # [Anggota 4] Layout Admin Panel
│       ├── auth.blade.php              # [Anggota 4] Layout Auth
│       └── public.blade.php            # [Anggota 4] Layout Publik
├── routes/
│   └── web.php                         # [Seluruh Anggota] Integrasi Routing & Middleware
├── tests/
│   └── Feature/
│       └── SipfkIntegrationTest.php    # [Anggota 5] 16 Automated Test Cases
└── docs/                               # Dokumentasi Teknis, Demo & Bukti Uji
```

---

## 9. HASIL PENGUJIAN OTOMATIS (TEST SUMMARY)

Semua skenario pengujian fungsional dan integrasi antar anggota telah diverifikasi menggunakan PHPUnit / Pest:
```
PASS  Tests\Feature\ExampleTest (1 test)
PASS  Tests\Unit\ExampleTest (1 test)
PASS  Tests\Feature\SipfkIntegrationTest (14 tests)
----------------------------------------------------------------------
Total: 16 passed, 62 assertions, Duration: ~1.4s (100% Success)
```
Aplikasi dinyatakan siap untuk dievaluasi dan didemonstrasikan dalam sidang TRAS Pemrograman Web Kelompok G5.
