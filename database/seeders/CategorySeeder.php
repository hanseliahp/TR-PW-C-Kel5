<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Ruang Kelas',          'description' => 'Kerusakan papan tulis, proyektor, meja, kursi, AC, atau lampu di ruang kelas.'],
            ['name' => 'Laboratorium',         'description' => 'Masalah komputer, jaringan, atau peralatan di laboratorium.'],
            ['name' => 'Toilet',               'description' => 'Kerusakan atau kebersihan toilet, keran, dan saluran air.'],
            ['name' => 'Perpustakaan',         'description' => 'Masalah fasilitas, rak, atau pendingin ruangan perpustakaan.'],
            ['name' => 'Area Parkir',          'description' => 'Kerusakan area parkir, rambu, atau penerangan parkir.'],
            ['name' => 'Jaringan & Wi-Fi',     'description' => 'Gangguan koneksi Wi-Fi atau jaringan kampus.'],
            ['name' => 'Listrik & Penerangan', 'description' => 'Lampu mati, korsleting, atau gangguan kelistrikan.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
