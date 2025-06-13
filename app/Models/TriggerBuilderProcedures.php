<?php

namespace App\Models;

class TriggerBuilderProcedures
{
    public static function procLogMenuUpdate()
    {
        return <<<SQL
        CREATE PROCEDURE log_menu_update(
            IN p_m_id CHAR(36), IN p_old_name VARCHAR(60), IN p_new_name VARCHAR(60),
            IN p_old_price DECIMAL(12,2), IN p_new_price DECIMAL(12,2),
            IN p_old_stock INT, IN p_new_stock INT
        )
        BEGIN
            INSERT INTO menu_update_logs (m_id, old_name, new_name, old_price, new_price, old_stock, new_stock)
            VALUES (p_m_id, p_old_name, p_new_name, p_old_price, p_new_price, p_old_stock, p_new_stock);
        END;
        SQL;
    }

    public static function procLogTransactionStatus()
    {
        return <<<SQL
        CREATE PROCEDURE log_transaction_status(
            IN p_t_id CHAR(36), IN p_old_status VARCHAR(60), IN p_new_status VARCHAR(60)
        )
        BEGIN
            INSERT INTO transaction_status_logs (t_id, old_status, new_status)
            VALUES (p_t_id, p_old_status, p_new_status);
        END;
        SQL;
    }

    public static function procUpdateDailyRevenue()
    {
        return <<<SQL
    CREATE PROCEDURE proc_update_daily_revenue(
        IN p_t_id CHAR(36)
    )
    BEGIN
        INSERT INTO daily_revenue_logs (log_date, v_id, total_revenue)
        SELECT 
            DATE(t.t_time) AS log_date,
            m.v_id,
            SUM(td.td_quantity * m.m_price) AS total_revenue
        FROM transactions t
        JOIN transaction_details td ON t.t_id = td.t_id
        JOIN menus m ON td.m_id = m.m_id
        WHERE t.t_id = p_t_id AND t.t_status = 'Selesai'
        GROUP BY DATE(t.t_time), m.v_id
        ON DUPLICATE KEY UPDATE 
            total_revenue = total_revenue + VALUES(total_revenue);
    END;
    SQL;
    }
    
    public static function procUpdateWeeklyRevenue()
    {
        return <<<SQL
    CREATE PROCEDURE proc_update_weekly_revenue(
        IN p_t_id CHAR(36)
    )
    BEGIN
        INSERT INTO weekly_revenue_logs (log_week_start, v_id, total_revenue)
        SELECT
            DATE_SUB(DATE(t.t_time), INTERVAL WEEKDAY(DATE(t.t_time)) DAY) AS log_week_start,
            m.v_id,
            SUM(td.td_quantity * m.m_price) AS total_revenue
        FROM transactions t
        JOIN transaction_details td ON td.t_id = t.t_id
        JOIN menus m               ON m.m_id = td.m_id
        WHERE t.t_id = p_t_id
        AND t.t_status = 'Selesai'
        GROUP BY log_week_start, m.v_id
        ON DUPLICATE KEY UPDATE
            total_revenue = total_revenue + VALUES(total_revenue);
    END;
    SQL;
    }


    public static function procUpdateMonthlyRevenue()
    {
        return <<<SQL
    CREATE PROCEDURE proc_update_monthly_revenue(
        IN p_t_id CHAR(36)
    )
    BEGIN
        INSERT INTO monthly_revenue_logs (log_month, v_id, total_revenue)
        SELECT 
            DATE_FORMAT(t.t_time, '%Y-%m') AS log_month,
            m.v_id,
            SUM(td.td_quantity * m.m_price) AS total_revenue
        FROM transactions t
        JOIN transaction_details td ON t.t_id = td.t_id
        JOIN menus m ON td.m_id = m.m_id
        WHERE t.t_id = p_t_id AND t.t_status = 'Selesai'
        GROUP BY DATE_FORMAT(t.t_time, '%Y-%m'), m.v_id
        ON DUPLICATE KEY UPDATE 
            total_revenue = total_revenue + VALUES(total_revenue);
    END;
    SQL;
    }
    
    

    public static function procReduceUserPoints()
    {
        return <<<SQL
        CREATE PROCEDURE proc_reduce_user_points(
            IN p_user_id CHAR(36), IN p_discount DECIMAL(12,2)
        )
        BEGIN
            UPDATE users SET point = point - FLOOR(p_discount) WHERE id = p_user_id;
            INSERT INTO user_points_logs (user_id, change_amount, event)
            VALUES (p_user_id, -FLOOR(p_discount), 'discount_applied');
        END;
        SQL;
    }

    public static function procAddUserPoints()
    {
        return <<<SQL
        CREATE PROCEDURE proc_add_user_points(
            IN p_user_id CHAR(36)
        )
        BEGIN
            UPDATE users SET point = point + 1 WHERE id = p_user_id;
            INSERT INTO user_points_logs (user_id, change_amount, event)
            VALUES (p_user_id, 1, 'transaction_complete');
        END;
        SQL;
    }

    public static function procReduceStock()
    {
        return <<<SQL
        CREATE PROCEDURE proc_reduce_stock(IN p_t_id CHAR(36))
        BEGIN
            UPDATE menus m
            JOIN transaction_details td ON td.m_id = m.m_id
            SET m.m_stock = m.m_stock - td.td_quantity
            WHERE td.t_id = p_t_id;
        END;
        SQL;
    }
    

    public static function procAddVendorEarnings()
    {
        return <<<SQL
        CREATE PROCEDURE proc_add_vendor_earnings(
            IN p_t_id CHAR(36)
        )
        BEGIN
            INSERT INTO vendor_earnings_logs (vendor_id, amount)
            SELECT m.v_id, SUM(m.m_price * td.td_quantity)
            FROM transaction_details td
            JOIN menus m ON m.m_id = td.m_id
            WHERE td.t_id = p_t_id
            GROUP BY m.v_id
            ON DUPLICATE KEY UPDATE 
                amount = amount + VALUES(amount);
        END;
        SQL;
    }
    
}

