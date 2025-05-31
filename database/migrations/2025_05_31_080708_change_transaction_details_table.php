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
        Schema::dropIfExists('transaction_details');

        Schema::create('transaction_details', function (Blueprint $table) {
            $table->uuid('t_id');
            $table->uuid('m_id');
            $table->integer('td_quantity');

            $table->primary(['t_id', 'm_id']);

            $table->foreign('t_id')->references('t_id')->on('transactions')->onDelete('cascade');            
            $table->foreign('m_id')->references('m_id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            //
        });
    }
};
