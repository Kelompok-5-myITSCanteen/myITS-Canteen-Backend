<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionDetail>
 */
class TransactionDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            't_id' => \App\Models\Transaction::factory(),
            'm_id' => \App\Models\Menu::factory(),
            'td_quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}