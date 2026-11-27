<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,      // 1. akun admin
            CategorySeeder::class,  // 2. kategori fasilitas
            ComplaintSeeder::class, // 3. pengaduan + tindak lanjut dummy
        ]);
    }
}
