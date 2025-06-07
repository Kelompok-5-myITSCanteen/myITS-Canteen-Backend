<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chair>
 */
class ChairFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ch_number' => $this->faker->numberBetween(1, 10),
            'tb_id' => \App\Models\Table::factory(),
            'k_id' => \App\Models\Canteen::factory(),
        ];
    }
}
