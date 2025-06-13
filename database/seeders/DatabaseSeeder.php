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
use App\Models\ChairReservation;

use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\QueryBuilder;
use Illuminate\Validation\Rules\Can;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        User::factory(30)->create();
        
        User::factory()->create([
            'name' => 'User Tester',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
            'point' => 1000,
        ])->assignRole('user');

        // Create a vendor account for testing
        $VendorAccount = User::factory()->create([
            'name' => 'Vendor',
            'email' => 'vendor@gmail.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');
        $InformatikaCanteen = Canteen::factory()->create([
            'k_name' => 'Kantin Informatika ITS',
        ]);
        $TestVendor = Vendor::factory()->create([
            'v_name' => 'Vendor Tester',
            'k_id' => $InformatikaCanteen->id, 
            'c_id' => $VendorAccount->id,
        ]);

        // Create 80 transactions for testing
        for ($i = 0; $i < 50; $i++) {
            $transaction = Transaction::factory()->create([
                'u_id' => User::inRandomOrder()->first()->id,
                'v_id' => $TestVendor->id,
            ]);
            // Create 3 transaction details for each transaction
            for ($j = 0; $j < 3; $j++) {
                TransactionDetail::factory()->create([
                    't_id' => $transaction->id,
                    'm_id' => Menu::inRandomOrder()->first()->id,
                ]);
            }
        }
        // Create 20 reservations for testing
        for ($i = 0; $i < 20; $i++) {
            $reservation = Reservation::factory()->create([
                'u_id' => User::inRandomOrder()->first()->id,
                'k_id' => Canteen::inRandomOrder()->first()->id,
            ]);
            // Create 2 chair reservations for each reservation
            for ($j = 0; $j < 2; $j++) {
                ChairReservation::factory()->create([
                    'r_id' => $reservation->id,
                    't_id' => Table::inRandomOrder()->first()->id,
                ]);
            }
        }


        User::factory(30)->create();
        $InformatikaCanteen = Canteen::factory()->create([
            'k_name' => 'Kantin Informatika ITS',
        ]);

        for ($i = 0; $i < 19; $i++) {
            $canteen = canteen::factory()->create();

            // create 6 tables for each canteen
            for ($j = 0; $j < 6; $j++) {
                $table = Table::factory()->create([
                    'k_id' => $canteen->k_id,
                ]);

                // create 4 chairs for each table
                for ($k = 0; $k < 4; $k++) {
                    Chair::factory()->create([
                        't_id' => $table->t_id,
                    ]);
                }
            }

            // create 2 vendors for each canteen
            for ($j = 0; $j < 2; $j++) {
                $vendor = Vendor::factory()->create([
                    'k_id' => $canteen->k_id,
                ]);

                // create 5 menus for each vendor
                for ($k = 0; $k < 5; $k++) {
                    Menu::factory()->create([
                        'v_id' => $vendor->v_id,
                    ]);
                }
            }
        }   


    }
}
