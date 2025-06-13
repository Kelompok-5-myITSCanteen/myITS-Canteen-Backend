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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create regular users
        $users = User::factory(20)->create();
        foreach ($users as $user) {
            $user->assignRole('user');
        }

        // Create test user
        $testUser = User::factory()->create([
            'name' => 'User Tester',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password'),
            'point' => 1000,
        ]);
        $testUser->assignRole('user');

        // Create vendor account
        $vendorAccount = User::factory()->create([
            'name' => 'Vendor',
            'email' => 'vendor@gmail.com',
            'password' => bcrypt('password'),
        ]);
        $vendorAccount->assignRole('admin');

        // Create Informatika canteen
        $informatikaCanteen = Canteen::factory()->create([
            'k_name' => 'Kantin Informatika ITS',
        ]);

        // Create additional canteens for variety
        $canteens = Canteen::factory(10)->create();
        $allCanteens = collect([$informatikaCanteen])->concat($canteens);

        // Create vendors
        $vendors = Vendor::factory(10)->create([
            'k_id' => $informatikaCanteen->k_id,
        ]);

        // Create test vendor
        $testVendor = Vendor::factory()->create([
            'v_name' => 'Vendor Tester',
            'k_id' => $informatikaCanteen->k_id,
            'c_id' => $vendorAccount->id,
        ]);

        $allVendors = $vendors->concat([$testVendor]);

        // Create menus for each vendor
        foreach ($allVendors as $vendor) {
            Menu::factory(rand(5, 15))->create([
                'v_id' => $vendor->v_id,
            ]);
        }

        // Create tables
        $tables = Table::factory(20)->create();

        // Create chairs for each canteen
        foreach ($allCanteens as $canteen) {
            Chair::factory(rand(10, 20))->create([
                'k_id' => $canteen->k_id,
            ]);
        }

        $allChairs = Chair::all();

        // Create transactions with proper relationships
        $allUsers = User::all();
        $transactions = collect();

        // Create transactions for test vendor
        for ($i = 0; $i < 60; $i++) {
            $transaction = Transaction::factory()->create([
                'c_id' => $testVendor->c_id,
            ]);
            $transactions->push($transaction);
        }

        // Create transaction details for each transaction
        $allMenus = Menu::all();
        foreach ($transactions as $transaction) {
            $detailCount = rand(1, 4);
            for ($j = 0; $j < $detailCount; $j++) {
                TransactionDetail::factory()->create([
                    't_id' => $transaction->t_id,
                    'm_id' => $allMenus->random()->m_id,
                ]);
            }
        }

        // Create reservations
        $reservations = collect();
        for ($i = 0; $i < 20; $i++) {
            $reservation = Reservation::factory()->create();
            $reservations->push($reservation);
        }

        // Create chair reservations for each reservation
        foreach ($reservations as $reservation) {
            $chairCount = rand(1, 3);
            $availableChairs = $allChairs->where('k_id', $reservation->k_id);
            
            if ($availableChairs->count() > 0) {
                $selectedChairs = $availableChairs->random(min($chairCount, $availableChairs->count()));
                
                foreach ($selectedChairs as $chair) {
                    ChairReservation::factory()->create([
                        'r_id' => $reservation->r_id,
                        'chair_id' => $chair->chair_id,
                    ]);
                }
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}