<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\TriggerBuilderTables;
use App\Models\TriggerBuilderProcedures;
use App\Models\TriggerBuilderTriggers;

return new class extends Migration
{
    public function up(): void
    {
        // Tables
        DB::statement(TriggerBuilderTables::createMenuUpdateLogTable());
        DB::statement(TriggerBuilderTables::createTransactionStatusLogTable());
        DB::statement(TriggerBuilderTables::createDailyRevenueLogTable());
        DB::statement(TriggerBuilderTables::createWeeklyRevenueLogTable());
        DB::statement(TriggerBuilderTables::createMonthlyRevenueLogTable());
        DB::statement(TriggerBuilderTables::createUserPointsLogTable());
        DB::statement(TriggerBuilderTables::createVendorEarningsLogTable());

        // Procedures
        DB::statement('DROP PROCEDURE IF EXISTS log_menu_update');
        DB::statement(TriggerBuilderProcedures::procLogMenuUpdate());

        DB::statement('DROP PROCEDURE IF EXISTS log_transaction_status');
        DB::statement(TriggerBuilderProcedures::procLogTransactionStatus());

        DB::statement('DROP PROCEDURE IF EXISTS proc_update_daily_revenue');
        DB::statement(TriggerBuilderProcedures::procUpdateDailyRevenue());

        DB::statement('DROP PROCEDURE IF EXISTS proc_update_weekly_revenue');
        DB::statement(TriggerBuilderProcedures::procUpdateWeeklyRevenue());

        DB::statement('DROP PROCEDURE IF EXISTS proc_update_monthly_revenue');
        DB::statement(TriggerBuilderProcedures::procUpdateMonthlyRevenue());

        DB::statement('DROP PROCEDURE IF EXISTS proc_reduce_user_points');
        DB::statement(TriggerBuilderProcedures::procReduceUserPoints());

        DB::statement('DROP PROCEDURE IF EXISTS proc_add_user_points');
        DB::statement(TriggerBuilderProcedures::procAddUserPoints());

        DB::statement('DROP PROCEDURE IF EXISTS proc_reduce_stock');
        DB::statement(TriggerBuilderProcedures::procReduceStock());

        DB::statement('DROP PROCEDURE IF EXISTS proc_add_vendor_earnings');
        DB::statement(TriggerBuilderProcedures::procAddVendorEarnings());

        // Triggers
        DB::statement('DROP TRIGGER IF EXISTS tr_after_menu_update');
        DB::statement(TriggerBuilderTriggers::triggerAfterMenuUpdate());

        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_status');
        DB::statement(TriggerBuilderTriggers::triggerAfterTransactionStatusChange());

        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_insert_daily');
        DB::statement(TriggerBuilderTriggers::triggerAfterTransactionInsertDaily());

        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_insert_weekly');
        DB::statement(TriggerBuilderTriggers::triggerAfterTransactionInsertWeekly());

        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_insert_monthly');
        DB::statement(TriggerBuilderTriggers::triggerAfterTransactionInsertMonthly());

        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_discount');
        DB::statement(TriggerBuilderTriggers::triggerAfterTransactionDiscount());

        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_add_points');
        DB::statement(TriggerBuilderTriggers::triggerAfterTransactionAddPoints());

        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_vendor_earnings');
        DB::statement(TriggerBuilderTriggers::triggerAfterTransactionVendorEarnings());

        // Trigger
        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_reduce_stock');
        DB::statement(TriggerBuilderTriggers::triggerAfterTransactionReduceStock());

    }

    public function down(): void
    {
        // Drop triggers
        DB::statement('DROP TRIGGER IF EXISTS tr_after_menu_update');
        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_status');
        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_insert_daily');
        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_insert_monthly');
        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_discount');
        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_add_points');
        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_vendor_earnings');
        DB::statement('DROP TRIGGER IF EXISTS tr_after_transaction_reduce_stock');

        // Drop procedures
        DB::statement('DROP PROCEDURE IF EXISTS log_menu_update');
        DB::statement('DROP PROCEDURE IF EXISTS log_transaction_status');
        DB::statement('DROP PROCEDURE IF EXISTS proc_update_daily_revenue');
        DB::statement('DROP PROCEDURE IF EXISTS proc_update_monthly_revenue');
        DB::statement('DROP PROCEDURE IF EXISTS proc_reduce_user_points');
        DB::statement('DROP PROCEDURE IF EXISTS proc_add_user_points');
        DB::statement('DROP PROCEDURE IF EXISTS proc_reduce_stock');
        DB::statement('DROP PROCEDURE IF EXISTS proc_add_vendor_earnings');

        // Drop tables
        Schema::dropIfExists('menu_update_logs');
        Schema::dropIfExists('transaction_status_logs');
        Schema::dropIfExists('daily_revenue_logs');
        Schema::dropIfExists('monthly_revenue_logs');
        Schema::dropIfExists('user_points_logs');
        Schema::dropIfExists('vendor_earnings_logs');
    }
};