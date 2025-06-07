<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\canteen>
 */
class CanteenFactory extends Factory
{

    protected $model = \App\Models\Canteen::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'k_name' => $this->faker->company(),
            'k_address' => $this->faker->address(),
        ];
    }
}