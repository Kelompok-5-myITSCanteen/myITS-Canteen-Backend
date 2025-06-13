<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            't_time' => $this->faker->dateTimeBetween('-5 month', 'now'),
            't_is_dine' => $this->faker->boolean(),
            't_total' => $this->faker->randomFloat(2, 10000, 100000),
            't_discount' => $this->faker->randomFloat(2, 0, 10000),
            't_payment' => $this->faker->randomElement(['cash', 'qris', 'card']),
            't_status' => $this->faker->randomElement(['Menunggu Konfirmasi', 'Selesai', 'Ditolak']),
            'c_id' => \App\Models\User::factory(),
        ];
    }
}
