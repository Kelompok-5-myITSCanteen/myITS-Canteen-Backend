<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Makanan', 'Minuman', 'Snack'];
        $category = $this->faker->randomElement($categories);

        $menuNames = [
            'Makanan' => [
                'Nasi Gudeg', 'Nasi Padang', 'Nasi Goreng', 'Mie Ayam', 'Bakso',
                'Soto Ayam', 'Gado-gado', 'Pecel Lele', 'Ayam Bakar', 'Rendang',
                'Nasi Rawon', 'Sate Ayam', 'Nasi Liwet', 'Ayam Geprek', 'Fried Rice'
            ],
            'Minuman' => [
                'Es Teh', 'Es Jeruk', 'Kopi Hitam', 'Teh Tarik', 'Es Campur',
                'Jus Alpukat', 'Es Kelapa Muda', 'Cappuccino', 'Lemon Tea', 'Air Mineral',
                'Jus Mangga', 'Es Dawet', 'Kopi Susu', 'Teh Hangat', 'Smoothie'
            ],
            'Snack' => [
                'Keripik Singkong', 'Pisang Goreng', 'Tahu Isi', 'Risoles', 'Martabak Mini',
                'Onde-onde', 'Kue Putu', 'Lemper', 'Klepon', 'Donat',
                'Pastel', 'Kroket', 'Batagor', 'Siomay', 'Cireng'
            ]
        ];


        return [
            'm_category' => $category,
            'm_name' => $this->faker->randomElement($menuNames[$category]),
            'm_price' => $this->faker->randomFloat(2, 5000, 40000),
            'm_stock'=> $this->faker->numberBetween(1, 100),
            'v_id' => \App\Models\Vendor::factory(),
        ];
    }
}
