<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name'           => 'Konveksi Hijab Bandung Utama',
                'contact_person' => 'Ibu Siti Aminah',
                'phone'          => '081234567890',
                'email'          => 'siti@konveksibandung.com',
                'address'        => 'Jl. Cigondewah No. 45, Bandung',
            ],
            [
                'name'           => 'Sentra Tekstil & Hijab Solo',
                'contact_person' => 'Bapak Hartono',
                'phone'          => '081987654321',
                'email'          => 'hartono@hijabsolo.co.id',
                'address'        => 'Kawasan Pasar Klewer Blok B-12, Solo',
            ],
            [
                'name'           => 'Grosir Voal & Ceruty Pekalongan',
                'contact_person' => 'Mbak Fitria',
                'phone'          => '085712341234',
                'email'          => 'fitria@pekalonganvoal.com',
                'address'        => 'Jl. Urip Sumoharjo No. 88, Pekalongan',
            ],
        ];

        foreach ($suppliers as $item) {
            Supplier::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
