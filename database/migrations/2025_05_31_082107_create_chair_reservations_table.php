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
        Schema::create('chair_reservations', function (Blueprint $table) {
            $table->uuid('ch_id');
            $table->uuid('r_id');

            $table->primary(['ch_id', 'r_id']);

            $table->foreign('ch_id')->references('ch_id')->on('chairs')->onDelete('cascade');
            $table->foreign('r_id')->references('r_id')->on('reservations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_reservations');
    }
};
