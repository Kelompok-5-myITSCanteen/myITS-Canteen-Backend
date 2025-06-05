<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Canteen;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Table;
use App\Models\Chair;
use App\Models\Reservation;
use App\Models\TableReservation;

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
            'point' => 1000,
        ])->assignRole('user');

        $kosteAdmin = User::factory()->create([
            'name' => 'Admin La Koste',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::factory(10)->create();

        $kosteCanteen = Canteen::factory()->create([
            'k_name' => 'Kantin La Koste',
            'k_address' => 'Jl. Raya La Koste No. 1'
        ]);

        $vendors = Vendor::factory()->count(5)->create([
            'k_id' => $kosteCanteen->k_id,
            'c_id' => $kosteAdmin->id,
        ]);

        foreach ($vendors as $vendor){
            Menu::factory()->count(5)->create([
                'v_id' => $vendor->v_id,
            ]);
        }

        $tables = Table::factory()->count(5)->create();

        foreach ($tables as $table) {
            for ($i = 1; $i <= 5; $i++) {
                Chair::factory()->create([
                    'tb_id' => $table->tb_id,
                    'k_id' => $kosteCanteen->k_id,
                    'ch_number' => $i,
                ]);
            }
        }

        $this->call([
            CanteenSeeder::class,
            // VendorSeeder::class,
        ]);
    }
}
