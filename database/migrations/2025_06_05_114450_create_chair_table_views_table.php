<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW chair_table_view AS
            SELECT 
                c.ch_id,
                c.ch_number,
                c.tb_id,
                c.k_id,
                t.tb_char,
                CONCAT(t.tb_char, c.ch_number) AS chair_name
            FROM chairs c
            INNER JOIN tables t ON c.tb_id = t.tb_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS chair_table_view");
    }
};
