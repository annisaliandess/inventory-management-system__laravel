<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Bersihkan nama kategori yang ada
        foreach (Category::all() as $cat) {
            $cat->name = trim($cat->name);
            $cat->save();
        }

        $categories = [
            'Pashmina',
            'Hijab Segi Empat',
            'Hijab Instan',
            'Bergo',
            'Khimar',
            'Pashmina Inner',
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat]);
        }
    }
}
