<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $materials = ['Ceruty Babydoll', 'Voal Premium', 'Jersey ITY', 'Silk Satin', 'Diamond Italiano', 'Rayon Twill', 'Plisket Premium'];
        $colors = ['Lilac', 'Mauve', 'Dusty Pink', 'Sage Green', 'Mocca', 'Navy', 'Maroon', 'Black', 'Broken White', 'Terracotta'];
        $models = ['Pashmina', 'Segi Empat', 'Bergo', 'Hijab Instan', 'Khimar'];

        $productName = fake()->randomElement($models) . ' ' . fake()->randomElement($materials) . ' ' . fake()->randomElement($colors);

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'supplier_id' => Supplier::inRandomOrder()->first()?->id ?? Supplier::factory(),
            'sku'         => 'HJB-' . strtoupper(fake()->bothify('???-###')),
            'name'        => $productName,
            'material'    => fake()->randomElement($materials),
            'price'       => fake()->randomElement([25000, 35000, 45000, 50000, 65000, 75000, 89000, 110000]),
            'stock'       => fake()->numberBetween(0, 120),
            'description' => fake()->optional()->sentence(8),
        ];
    }
}
