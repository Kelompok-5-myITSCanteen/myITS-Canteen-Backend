<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'customer']);
        Role::firstOrCreate(['name' => 'vendor']);

        User::factory()->create([
            'name' => 'Kevin Andreas',
            'email' => 'kevin.andreascn@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('customer');

        User::factory()->create([
            'name' => 'Nathan Valen',
            'email' => 'nathanvalen@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('vendor');

        User::factory()->create([
            'name' => 'Admin La Koste',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::factory(10)->create();

        $this->call([
            CanteenSeeder::class,
        ]);
    }
}
