<?php

namespace App\Models;

class QueryBuilder
{
    public static function initMenuByVendorView()
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

    public static function initTransactionMenuView()
    {
        return ("
            CREATE OR REPLACE VIEW transaction_menu_view AS
            SELECT *, 
                (TD.td_quantity * M.m_price) AS total_price
            FROM transaction_details TD
            NATURAL JOIN menus M
        ");
    }

    public static function initVendorEarningsView()
    {
        return ("
            CREATE OR REPLACE VIEW vendor_earnings_view AS
            SELECT SUM(TD.td_quantity * M.m_price) AS total_earnings,
                M.v_id,
                DATE(T.t_time) AS transaction_date
            FROM transaction_details TD
            NATURAL JOIN menus M
            NATURAL JOIN transactions T
            WHERE DATE(T.t_time) = CURRENT_DATE
            GROUP BY M.v_id, DATE(T.t_time)
        ");
    }
}
