<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\QueryBuilder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(QueryBuilder::initChairTableView());
        DB::statement(QueryBuilder::initTransactionMenuView());
        DB::statement(QueryBuilder::initTransactionReservationView());
        DB::statement(QueryBuilder::initVendorTransactionCountView());
        DB::statement(QueryBuilder::initVendorPurchasedMenusView());
        DB::statement(QueryBuilder::initVendorUniqueCustomerCountView());
        DB::statement(QueryBuilder::initVendorEarningsView());
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS chair_table_view");
    }
};
