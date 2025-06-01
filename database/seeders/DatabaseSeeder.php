<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Canteen;
use App\Models\Vendor;
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

        $tempAdmin = User::factory()->create([
            'name' => 'Admin La Koste',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::factory(10)->create();

        $tempCanteen = Canteen::factory()->create([
            'k_name' => 'Kantin La Koste',
            'k_address' => 'Jl. Raya La Koste No. 1'
        ]);

        Vendor::factory()->count(5)->create([
            'k_id' => $tempCanteen->k_id,
            'c_id' => $tempAdmin->id,
        ]);

        $this->call([
            CanteenSeeder::class,
            // VendorSeeder::class,
        ]);
    }
}
