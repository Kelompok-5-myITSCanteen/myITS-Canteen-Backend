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
        Schema::create('chairs', function (Blueprint $table) {
            $table->uuid('ch_id')->primary();
            $table->integer('ch_number');
            $table->uuid('k_id');
            $table->uuid('tb_id');

            $table->foreign('k_id')->references('k_id')->on('canteens')->onDelete('cascade');
            $table->foreign('tb_id')->references('tb_id')->on('tables')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chairs');
    }
};
