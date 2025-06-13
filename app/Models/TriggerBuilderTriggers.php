<?php

namespace App\Models;

class TriggerBuilderTriggers
{
    public static function triggerAfterMenuUpdate()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_menu_update
        AFTER UPDATE ON menus
        FOR EACH ROW
        BEGIN
            CALL log_menu_update(OLD.m_id, OLD.m_name, NEW.m_name,
                                OLD.m_price, NEW.m_price,
                                OLD.m_stock, NEW.m_stock);
        END;
        SQL;
    }


    public static function triggerAfterTransactionStatusChange()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_transaction_status
        AFTER UPDATE ON transactions
        FOR EACH ROW
        BEGIN
            IF OLD.t_status <> NEW.t_status THEN
                CALL log_transaction_status(OLD.t_id, OLD.t_status, NEW.t_status);
            END IF;
        END;
        SQL;
    }

    public static function triggerAfterTransactionInsertDaily()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_transaction_insert_daily
        AFTER UPDATE ON transactions
        FOR EACH ROW
        BEGIN
            IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' THEN
                CALL proc_update_daily_revenue(NEW.t_id);
            END IF;
        END;
        SQL;
    }

    public static function triggerAfterTransactionInsertWeekly()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_transaction_insert_weekly
        AFTER UPDATE ON transactions
        FOR EACH ROW
        BEGIN
            IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' THEN
                CALL proc_update_weekly_revenue(NEW.t_id);
            END IF;
        END;
        SQL;
    }

    public static function triggerAfterTransactionInsertMonthly()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_transaction_insert_monthly
        AFTER UPDATE ON transactions
        FOR EACH ROW
        BEGIN
            IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' THEN
                CALL proc_update_monthly_revenue(NEW.t_id);
            END IF;
        END;
        SQL;
    }

    public static function triggerAfterTransactionDiscount()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_transaction_discount
        AFTER UPDATE ON transactions
        FOR EACH ROW
        BEGIN
            IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' AND NEW.t_discount > 0 THEN
                CALL proc_reduce_user_points(NEW.c_id, NEW.t_discount);
            END IF;
        END;
        SQL;
    }

    public static function triggerAfterTransactionAddPoints()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_transaction_add_points
        AFTER UPDATE ON transactions
        FOR EACH ROW
        BEGIN
            IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' THEN
                CALL proc_add_user_points(NEW.c_id);
            END IF;
        END;
        SQL;
    }

    public static function triggerAfterTransactionVendorEarnings()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_transaction_vendor_earnings
        AFTER UPDATE ON transactions
        FOR EACH ROW
        BEGIN
            IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' THEN
                CALL proc_add_vendor_earnings(NEW.t_id);
            END IF;
        END;
        SQL;
    }

    public static function triggerAfterTransactionReduceStock()
    {
        return <<<SQL
        CREATE TRIGGER tr_after_transaction_reduce_stock
        AFTER UPDATE ON transactions
        FOR EACH ROW
        BEGIN
            IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' THEN
                CALL proc_reduce_stock(NEW.t_id);
            END IF;
        END;
        SQL;
    }
    
    
}
