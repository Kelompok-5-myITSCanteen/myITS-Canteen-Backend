<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TableReservation>
 */
class ChairReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ch_id' => \App\Models\Chair::factory(),
            'r_id' => \App\Models\Reservation::factory(),
        ];
    }
}
