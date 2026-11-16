<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sipfk.test'],
            [
                'name' => 'Admin SIPFK',
                'password' => Hash::make('password'), // ganti sebelum dipakai di luar demo
                'email_verified_at' => now(),
            ]
        );
    }
}
