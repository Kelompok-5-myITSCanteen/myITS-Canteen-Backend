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
        $canteenNames = [
        'Kantin Teknik Sipil',
        'Kantin Teknik Mesin',
        'Kantin Teknik Elektro',
        'Kantin Arsitektur',
        'Kantin Teknik Kimia',
        'Kantin Matematika',
        'Kantin Fisika',
        'Kantin Biologi',
        'Kantin Teknik Material',
        'Kantin Teknik Industri',
        'Kantin Teknik Perkapalan',
        'Kantin Teknik Kelautan',
        'Kantin Teknik Lingkungan',
        'Kantin Perencanaan Wilayah',
        'Kantin Sains Data',
        'Kantin Teknologi Informasi',
        'Kantin Sistem Informasi',
        'Kantin Desain Komunikasi',
        'Kantin Pusat ITS'
    ];
        return [
            'k_name' => $this->faker->unique()->randomElement($canteenNames),
            'k_address' => $this->faker->address(),
        ];
    }
}