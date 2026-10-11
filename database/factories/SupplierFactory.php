<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'           => 'Konveksi ' . fake()->company(),
            'contact_person' => fake()->name(),
            'phone'          => fake()->phoneNumber(),
            'email'          => fake()->safeEmail(),
            'address'        => fake()->address(),
        ];
    }
}
