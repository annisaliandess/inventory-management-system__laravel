<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Default Pengujian
        User::firstOrCreate(
            ['email' => 'admin@tokohijab'],
            [
                'name'     => 'Admin Toko Hijab',
                'password' => Hash::make('password123'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'staff@tokohijab'],
            [
                'name'     => 'Staff Inventori',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Kategori Default
        $this->call(CategorySeeder::class);

        // 3. Supplier Default
        $this->call(SupplierSeeder::class);

        // 4. Data Dummy Produk Menggunakan ProductFactory (jika belum ada cukup produk)
        if (Product::count() < 5) {
            Product::factory()->count(10)->create();
        }
    }
}
