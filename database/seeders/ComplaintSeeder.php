<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@sipfk.test')->firstOrFail();
        $cat = Category::pluck('id', 'name');

        $data = [

            ['Ruang Kelas', 'Andi Pratama', 'Gedung A Ruang 201', '2026-11-10', 'Proyektor tidak menyala saat dipakai kuliah.', 'baru', []],
            ['Toilet', 'Siti Rahma', 'Gedung B Lantai 1', '2026-11-11', 'Keran wastafel bocor dan air menggenang.', 'baru', []],
            ['Jaringan & Wi-Fi', 'Budi Santoso', 'Perpustakaan Lantai 2', '2026-11-12', 'Wi-Fi sering terputus sehingga sulit mengakses jurnal.', 'baru', []],
            ['Area Parkir', 'Dewi Lestari', 'Parkir Motor Sisi Barat', '2026-11-13', 'Lampu penerangan parkir mati sehingga area gelap.', 'baru', []],
            ['Laboratorium', 'Rizky Maulana', 'Lab Komputer 3', '2026-11-14', 'Dua unit komputer tidak bisa booting.', 'baru', []],

            ['Ruang Kelas', 'Maria Gabriela', 'Gedung A Ruang 105', '2026-11-03', 'AC bocor dan menetes ke meja mahasiswa.', 'diproses',
                ['Laporan diterima, teknisi dijadwalkan memeriksa AC.']],
            ['Listrik & Penerangan', 'Yohanes Putra', 'Koridor Gedung C', '2026-11-04', 'Beberapa lampu koridor berkedip dan sering mati.', 'diproses',
                ['Tim listrik sudah survei lokasi.', 'Menunggu pengadaan lampu pengganti.']],
            ['Perpustakaan', 'Natalia Sari', 'Perpustakaan Lantai 1', '2026-11-05', 'Rak buku bagian referensi miring dan berbahaya.', 'diproses',
                ['Rak diberi tanda larangan akses sementara.']],
            ['Toilet', 'Fajar Nugroho', 'Gedung D Lantai 2', '2026-11-06', 'Saluran toilet tersumbat.', 'diproses',
                ['Petugas kebersihan sedang menangani penyumbatan.']],

            ['Ruang Kelas', 'Clara Wijaya', 'Gedung B Ruang 302', '2026-10-28', 'Kursi patah pada baris depan.', 'selesai',
                ['Kursi diganti dengan yang baru.', 'Pengecekan ulang: kondisi aman.']],
            ['Jaringan & Wi-Fi', 'Daniel Kristian', 'Aula Utama', '2026-10-29', 'Access point Wi-Fi tidak aktif.', 'selesai',
                ['Access point direstart dan dikonfigurasi ulang. Sudah normal.']],
            ['Laboratorium', 'Eka Putri', 'Lab Jaringan', '2026-10-30', 'Kabel LAN rusak pada meja 4.', 'selesai',
                ['Kabel LAN diganti. Koneksi sudah normal.']],
        ];

        foreach ($data as [$category, $reporter, $location, $date, $description, $status, $notes]) {
            $complaint = Complaint::create([
                'category_id' => $cat[$category],
                'reporter_name' => $reporter,
                'location' => $location,
                'reported_at' => $date,
                'description' => $description,
                'status' => $status,
            ]);

            foreach ($notes as $note) {
                $complaint->followUps()->create([
                    'admin_id' => $admin->id,
                    'note' => $note,
                ]);
            }
        }
    }
}
