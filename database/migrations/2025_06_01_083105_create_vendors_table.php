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
        Schema::create('vendors', function (Blueprint $table) {
            $table->uuid('v_id')->primary();
            $table->string('v_name');
            $table->timestamp('v_join_date');
            $table->uuid('k_id');
            $table->uuid('c_id');

            $table->foreign('k_id')->references('k_id')->on('canteens')->onDelete('cascade');
            $table->foreign('c_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
