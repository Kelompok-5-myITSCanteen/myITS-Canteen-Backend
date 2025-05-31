<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Canteen;
use Illuminate\Database\Seeder;

class CanteenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Canteen::factory()->count(10)->create();
    }
}
