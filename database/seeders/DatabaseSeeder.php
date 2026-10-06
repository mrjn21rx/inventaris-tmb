<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Panggil seeder kategori
        $this->call(CategorySeeder::class);

        // Buat Akun Super Admin
        User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@app.com',
            'password' => Hash::make('password123'),
            'role' => 'super-admin' // Kita tambahkan penanda role sederhana
        ]);

        // Buat Akun Admin
        User::create([
            'name' => 'Admin Gudang',
            'email' => 'admin@app.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);
    }
}