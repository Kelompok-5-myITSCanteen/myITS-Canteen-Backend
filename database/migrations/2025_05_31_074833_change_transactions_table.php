<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('transactions');

        Schema::create('transactions', function (Blueprint $table) {
            $table->char('t_id', 36)->primary();
            $table->timestamp('t_time');
            $table->boolean('t_is_dine');
            $table->decimal('t_total', 12, 2);
            $table->decimal('t_discount', 12, 2)->nullable();
            $table->string('t_payment', 60);
            $table->string('t_status', 60);
            $table->uuid('c_id');

            $table->foreign('c_id')->references('id')->on('users')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
