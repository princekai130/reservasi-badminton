<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT USER ADMIN
        if (User::where('email', 'admin@reservasi.com')->doesntExist()) {
            User::create([
                'name' => 'Admin Utama',
                'email' => 'admin@reservasi.com',
                'password' => Hash::make('password'), 
                'is_admin' => true, 
            ]);
        }
        
        // 2. BUAT USER BIASA UNTUK TESTING
        if (User::where('email', 'user@reservasi.com')->doesntExist()) {
            User::create([
                'name' => 'User Biasa A',
                'email' => 'user@reservasi.com',
                'password' => Hash::make('password'), 
                'is_admin' => false, // <-- PENTING: Set sebagai User Biasa
            ]);
            echo "User Biasa A created successfully!\n";
        }
    }
}