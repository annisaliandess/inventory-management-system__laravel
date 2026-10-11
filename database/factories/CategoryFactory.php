<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Pashmina',
            'Hijab Segi Empat',
            'Hijab Instan',
            'Bergo',
            'Khimar',
            'Pashmina Plisket',
            'Pashmina Inner',
            'Pashmina Silk',
        ];

        return [
            'name' => fake()->unique()->randomElement($categories) . ' ' . fake()->randomNumber(2, false),
        ];
    }
}
