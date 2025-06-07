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
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid('m_id')->primary();
            $table->string('m_category', 60);
            $table->string('m_name', 60);
            $table->string('m_image', 255)->nullable();
            $table->decimal('m_price', 12, 2);
            $table->integer('m_stock');
            $table->uuid('v_id');

            $table->foreign('v_id')->references('v_id')->on('vendors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
