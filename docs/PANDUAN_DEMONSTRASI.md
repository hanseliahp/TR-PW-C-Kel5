# PANDUAN LENGKAP DEMONSTRASI TRAS (SEMUA ANGGOTA)
## SIPFK — SISTEM INFORMASI PENGADUAN FASILITAS KAMPUS
**Tugas Rancang Akhir Semester (TRAS) Pemrograman Web | Kelas G — Kelompok G5**

Dokumen ini disusun sebagai panduan simulasi dan alur presentasi lengkap 5 anggota di hadapan dosen penguji TRAS.

---

## 1. STRATEGI PEMBAGIAN WAKTU & SUSUNAN DEMONSTRASI TIM

Total estimasi waktu demonstrasi: **15 – 20 Menit**.

```
[ Pembuka & Arsitektur Database ] ───> [ Form Publik & CRUD Aduan ] ───> [ Status & Tindak Lanjut ] ───> [ UI Layout & Dashboard ] ───> [ Auth, PDF & Testing ]
          (Anggota 1)                            (Anggota 2)                        (Anggota 3)                    (Anggota 4)                  (Anggota 5)
```

---

## 2. SKENARIO DEMONSTRASI ANGGOTA DEMI ANGGOTA

### ANGGOTA 1 — ANALISIS DATABASE & FONDASI BACKEND
* **Tugas Pokok:** ERD, Migrasi tabel, Relasi Eloquent, Konfigurasi koneksi database, dan Seeder.
* **Langkah Demonstrasi:**
  1. Buka file skema database atau tunjukkan tabel pada HeidiSQL/phpMyAdmin (`users`, `categories`, `complaints`, `follow_ups`).
  2. Tunjukkan foreign key constraint: `complaints.category_id` ke `categories.id` dan `follow_ups.complaint_id` ke `complaints.id`.
  3. Buka terminal dan jelaskan seeder:
     ```bash
     php artisan db:seed --class=CategorySeeder
     php artisan db:seed --class=UserSeeder
     ```
* **Narasi ke Dosen:**
  > *"Selamat pagi/siang Bapak/Ibu dosen penguji. Saya bertanggung jawab pada fondasi database dan arsitektur data SIPFK. Kami merancang 4 tabel yang saling terintegrasi dengan integritas referensial. Tabel `complaints` terhubung ke `categories`, serta tabel `follow_ups` mencatat relasi one-to-many ke pengaduan dan admin yang menanganinya. Kami juga menyiapkan data master melalui seeder sehingga sistem siap dijalankan dalam kondisi konsisten."*
* **Prediksi Pertanyaan Dosen & Jawaban:**
  * **Q:** *Apa aturan penghapusan data (onDelete) pada relasi kategori ke pengaduan?*  
    **A:** *Relasi kategori ke pengaduan menggunakan aturan RESTRICT (BR-08), di mana kategori yang masih memiliki pengaduan tidak dapat dihapus sembarangan untuk menjaga integritas data riwayat fasilitas.*

---

### ANGGOTA 2 — CRUD KATEGORI DAN PENGADUAN
* **Tugas Pokok:** Form pengaduan publik, validasi formulir sisi server, kelola kategori, dan manajemen daftar pengaduan.
* **Langkah Demonstrasi:**
  1. Buka halaman utama `http://localhost:8000/` (Formulir Pengaduan Pelapor).
  2. Coba klik tombol *Kirim Pengaduan* dalam keadaan kosong untuk membuktikan validasi sisi server (FR-10).
  3. Isi data pengaduan baru:
     * Pelapor: *Muhammad Rizky*
     * Kategori: *Kelistrikan*
     * Lokasi: *Gedung C, Ruang Kuliah 302*
     * Tanggal: *Hari ini*
     * Deskripsi: *Stop kontak dinding korsleting dan terbakar.*
  4. Klik kirim dan tunjukkan pesan sukses serta status awal yang otomatis bernilai `baru`.
  5. Masuk ke panel admin `http://localhost:8000/admin/kategori` dan tunjukkan penambahan kategori baru serta penolakan penghapusan jika kategori masih aktif digunakan.
* **Narasi ke Dosen:**
  > *"Saya bertanggung jawab atas proses CRUD kategori dan pengaduan. Formulir pelapor dirancang dapat diakses publik tanpa login agar warga kampus dapat melapor dengan cepat. Seluruh input divalidasi di sisi server. Pada panel admin, saya menyediakan pengelolaan kategori yang menerapkan aturan BR-08, di mana kategori tidak dapat dihapus jika masih digunakan oleh pengaduan aktif."*
* **Prediksi Pertanyaan Dosen & Jawaban:**
  * **Q:** *Mengapa pelapor tidak diwajibkan mendaftar akun terlebih dahulu?*  
    **A:** *Berdasarkan keputusan PRD Bagian 3, formulir pengaduan dibuat tanpa akun agar menghilangkan friksi pelaporan fasilitas darurat di kampus. Namun keamanan tetap dijaga melalui validasi server dan pembatasan karakter input.*

---

### ANGGOTA 3 — ALUR STATUS, CATATAN TINDAK LANJUT & DASHBOARD
* **Tugas Pokok:** Manajemen status pengaduan (`baru` $\rightarrow$ `diproses` $\rightarrow$ `selesai`), form penambahan catatan tindak lanjut, visualisasi riwayat penanganan, dan logika perhitungan angka 4 metrik dashboard.
* **Langkah Demonstrasi:**
  1. Masuk ke menu *Daftar Pengaduan* `http://localhost:8000/admin/pengaduan`.
  2. Buka salah satu pengaduan yang berstatus `baru` (misal aduan yang baru dikirim Anggota 2).
  3. Ubah status pengaduan menjadi `diproses` dan klik simpan. Tunjukkan perubahan badge status secara visual (kuning $\rightarrow$ biru).
  4. Tambahkan catatan tindak lanjut pada form: *"Petugas teknisi kelistrikan sedang meluncur ke Gedung C membawa modul stop kontak pengganti."*
  5. Simpan catatan dan tunjukkan riwayat catatan yang langsung muncul di linimasa detail beserta nama admin (`Auth::user()->name`) dan stempel waktu input.
  6. Setelah itu, ubah status menjadi `selesai` dan tambahkan catatan penyelesaian: *"Stop kontak selesai diganti dan jalur kelistrikan telah normal kembali."* Tunjukkan bahwa satu pengaduan dapat menampung multi-catatan (*history trail* sesuai BR-06).
  7. Buka halaman *Dashboard Admin* `http://localhost:8000/admin/dashboard` dan jelaskan bagaimana nilai pada 4 kartu statistik dihitung secara *real-time* via `DashboardController` (`Complaint::count()`, kueri per-status `baru`, `diproses`, dan `selesai`).
* **Narasi ke Dosen:**
  > *"Saya menangani alur siklus penanganan pengaduan, catatan tindak lanjut, dan backend ringkasan dashboard. Melalui `ComplaintController@updateStatus`, admin kampus dapat memperbarui status secara bertahap dari baru, diproses, hingga selesai dengan validasi ketat yang menolak status di luar ketentuan (AC-05). Melalui `FollowUpController`, setiap tindakan perbaikan dicatat ke tabel `follow_ups` lengkap dengan identitas admin yang sedang login (`Auth::id()`) dan waktu pencatatan, sehingga ada histori pertanggungjawaban yang jelas dan transparan. Selain itu, pada `DashboardController`, saya mengimplementasikan logika agregasi database untuk menyajikan data statistik riil ke 4 kartu metrik dashboard."*
* **Prediksi Pertanyaan Dosen & Jawaban:**
  * **Q:** *Apakah satu pengaduan bisa memiliki lebih dari satu catatan tindak lanjut?*  
    **A:** *Ya, sesuai aturan BR-06, model `Complaint` memiliki relasi `hasMany` ke `FollowUp`. Hal ini memungkinkan admin mencatat setiap tahapan perbaikan secara berkala dari waktu ke waktu.*
  * **Q:** *Bagaimana cara Anda memastikan hanya admin yang sedang login yang tercatat sebagai penulis catatan?*  
    **A:** *Pada `FollowUpController@store`, nilai kolom `admin_id` diambil langsung dari sesi terautentikasi menggunakan `Auth::id()`, bukan dari input form tersembunyi (hidden input), sehingga tidak dapat dimanipulasi oleh pengguna.*
  * **Q:** *Bagaimana angka pada 4 kartu ringkasan dashboard dihitung?*  
    **A:** *Di `DashboardController@index`, metrik dihitung langsung menggunakan agregasi Eloquent: `Complaint::count()` untuk total, serta `Complaint::where('status', ...)->count()` untuk masing-masing status (`baru`, `diproses`, `selesai`). Hasilnya dikirim ke Blade view dalam bentuk array asosiatif `$stats`.*

---

### ANGGOTA 4 — ANTARMUKA PENGGUNA BLADE & BOOTSTRAP
* **Tugas Pokok:** Arsitektur tata letak Blade, styling kustom Bootstrap 5.3, komponen reusable (stat-card, badge, pagination, modal), dashboard 4 metrik statistik, dan responsivitas mobile.
* **Langkah Demonstrasi:**
  1. Buka halaman *Dashboard Admin* `http://localhost:8000/admin/dashboard`.
  2. Tunjukkan 4 Kartu Statistik utama (FR-08): *Total Pengaduan*, *Status Baru*, *Diproses*, dan *Selesai*. Buktikan bahwa angkanya sesuai dengan jumlah rekor riil pada database.
  3. Tunjukkan tabel *Recent Complaints* dengan badge visual berwarna semantik (kuning untuk baru, biru untuk diproses, hijau untuk selesai).
  4. Buka *Developer Tools* browser (F12) dan ubah ukuran viewport ke resolusi ponsel (misal iPhone / Samsung Galaxy).
  5. Tunjukkan menu sidebar yang bertransformasi menjadi **Bootstrap Offcanvas** yang mulus di layar ponsel, serta tabel yang responsif secara horizontal (`table-responsive`).
* **Narasi ke Dosen:**
  > *"Saya mendesain arsitektur antarmuka Blade dan sistem tata letak Bootstrap 5.3. Panel admin dilengkapi 4 metrik kartu statistik interaktif sesuai kebutuhan Section 5.1 PRD. Desain kami menjunjung tinggi aksesibilitas, konsistensi warna civic/kampus tanpa template siap pakai yang berlebihan, dan responsif penuh baik di layar komputer desktop pengelola maupun ponsel pintar pelapor di lapangan."*
* **Prediksi Pertanyaan Dosen & Jawaban:**
  * **Q:** *Bagaimana cara Anda menjaga konsistensi komponen di seluruh halaman?*  
    **A:** *Kami memecah UI menjadi komponen Blade modular di `resources/views/components/` seperti `stat-card.blade.php`, `status-badge.blade.php`, `alert.blade.php`, dan `delete-confirm-modal.blade.php` sehingga mudah dipanggil ulang dan seragam.*

---

### ANGGOTA 5 — AUTENTIKASI, MIDDLEWARE, LAPORAN PDF & TESTING
* **Tugas Pokok:** Sistem autentikasi admin (Login/Logout), proteksi middleware `auth`, generasi laporan PDF resmi, pengujian integrasi otomatis, dan dokumentasi TRAS.
* **Langkah Demonstrasi:**
  1. Buka browser baru / mode *Incognito* tanpa login, coba langsung akses `http://localhost:8000/admin/dashboard`.
  2. Tunjukkan bahwa middleware `auth` secara otomatis mencegat akses tidak sah dan me-redirect pengguna ke `http://localhost:8000/login`.
  3. Lakukan login menggunakan kredensial admin (`admin@sipfk.test` / `password`), tunjukkan proses *session regeneration* yang melindungi dari pembajakan sesi.
  4. Masuk ke menu *Laporan Pengaduan* `http://localhost:8000/admin/laporan`, gunakan filter tanggal dan status, lalu klik tombol **Unduh File PDF** dan **Pratinjau PDF**.
  5. Buka dokumen PDF yang terunduh: tunjukkan kop surat SIPFK, tabel pengaduan aktual, catatan follow-up, stempel pencetak, dan nomor halaman dinamis.
  6. Buka terminal dan jalankan seluruh unit & integration test otomatis:
     ```bash
     php artisan test
     ```
     Tunjukkan bahwa seluruh 16 pengujian (62 assertions) lolos 100% tanpa error.
  7. Lakukan logout untuk membuktikan penghancuran sesi dan pembaruan CSRF token.
* **Narasi ke Dosen:**
  > *"Saya bertanggung jawab atas lapisan keamanan, dokumen resmi, dan jaminan kualitas sistem. Seluruh rute administratif kami proteksi dengan middleware autentikasi. Untuk pelaporan, kami membangun generator PDF yang mengambil data relasi database terkini secara langsung. Sebagai penjamin mutu aplikasi, saya menyusun 16 skenario pengujian otomatis end-to-end yang menguji mulai dari form publik, autentikasi, middleware, hingga ekspor PDF dengan hasil 100% passed."*
* **Prediksi Pertanyaan Dosen & Jawaban:**
  * **Q:** *Bagaimana Anda memastikan file PDF dapat dibuka di sistem operasi mana pun tanpa error font?*  
    **A:** *Generator PDF kami mengompilasi dokumen ke standar compliant PDF 1.4 berstandar WinAnsiEncoding dengan font inti Type 1 (Helvetica & Helvetica-Bold) yang didukung oleh semua PDF viewer di seluruh sistem operasi.*

---

## 3. CHECKLIST KESIAPAN SEBELUM SIDANG TRAS

- [x] Web server lokal aktif (`php artisan serve`).
- [x] Database MySQL/MariaDB terhubung dan telah di-seed (`php artisan migrate:status`).
- [x] Kredensial demo siap diuji (`admin@sipfk.test` / `password`).
- [x] Browser disiapkan dengan bookmark rute utama (`/`, `/login`, `/admin/dashboard`, `/admin/laporan`).
- [x] Seluruh 5 anggota memahami alur kerja rekan setim dan siap menjawab pertanyaan silang dari dosen penguji.
