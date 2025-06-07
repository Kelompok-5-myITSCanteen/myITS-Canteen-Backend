<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'v_name' => $this->faker->company(),
            'v_join_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'k_id' => \App\Models\Canteen::factory(),
            'c_id' => \App\Models\User::factory(),
        ];
    }
}
