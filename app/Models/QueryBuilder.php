<?php

namespace App\Models;

class QueryBuilder
{
    public static function getQuery()
    {
        return ("
            CREATE OR REPLACE VIEW menuBy_vendorID as (
            SELECT m_id, m_name, m_category, m_price, m_stock
            FROM menus
            WHERE v_id = :vendorId
            );
        ");
    }

    public static function initChairTableView()
    {
        return ("
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

    
}
