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
        static $counter = 0;
        static $canteenIds = null;
        
        // Get all canteen IDs once
        if ($canteenIds === null) {
            $canteenIds = \App\Models\Canteen::pluck('k_id')->toArray();
            if (empty($canteenIds)) {
                // If no canteens exist, create one
                $canteen = \App\Models\Canteen::factory()->create();
                $canteenIds = [$canteen->k_id];
            }
        }
        
        // Round-robin distribution
        $k_id = $canteenIds[$counter % count($canteenIds)];
        $counter++;
        
        return [
            'v_name' => $this->faker->company(),
            'v_join_date' => $this->faker->dateTimeBetween('-5 year', 'now'),
            'k_id' => $k_id,
            'c_id' => \App\Models\User::factory(),
        ];
    }
}
