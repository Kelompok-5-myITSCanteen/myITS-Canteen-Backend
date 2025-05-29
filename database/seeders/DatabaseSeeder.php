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
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        User::factory()->create([
            'name' => 'Kevin Andreas',
            'email' => 'kevin.andreascn@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('user');

        User::factory()->create([
            'name' => 'Admin La Koste',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::factory(10)->create();
    }
}
