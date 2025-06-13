<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $timeIn = $this->faker->dateTimeBetween('-1 year', 'now');
        $timeOut = Carbon::parse($timeIn)->addHour(); // Add 1 hour

        return [
            'r_time_in' => $timeIn,
            'r_time_out' => $timeOut,
            't_id' => \App\Models\Transaction::factory(),
        ];
    }
}
