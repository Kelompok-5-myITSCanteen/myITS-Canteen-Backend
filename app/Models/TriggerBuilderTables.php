<?php

namespace App\Models;

class TriggerBuilderTables
{
    public static function createMenuUpdateLogTable()
    {
        return <<<SQL
CREATE TABLE IF NOT EXISTS menu_update_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    m_id CHAR(36) NOT NULL,
    old_name VARCHAR(60),
    new_name VARCHAR(60),
    old_price DECIMAL(12,2),
    new_price DECIMAL(12,2),
    old_stock INT,
    new_stock INT,
    changed_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'Asia/Jakarta')
);
SQL;
    }


    public static function createTransactionStatusLogTable()
    {
        return <<<SQL
    CREATE TABLE IF NOT EXISTS transaction_status_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    t_id CHAR(36) NOT NULL,
    old_status VARCHAR(60),
    new_status VARCHAR(60),
    changed_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'Asia/Jakarta')
    );
    SQL;
    }

    public static function createDailyRevenueLogTable()
    {
        return <<<SQL
    CREATE TABLE IF NOT EXISTS daily_revenue_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        log_date CHAR(10) NOT NULL,
        v_id CHAR(36) NOT NULL,
        total_revenue DECIMAL(14,2) NOT NULL,
        recorded_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'Asia/Jakarta'),
        UNIQUE (log_date, v_id)
    );
    SQL;
    }
    
    public static function createWeeklyRevenueLogTable()
    {
        return <<<SQL
    CREATE TABLE IF NOT EXISTS weekly_revenue_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        log_week_start CHAR(10) NOT NULL,     
        v_id CHAR(36) NOT NULL,
        total_revenue DECIMAL(16,2) NOT NULL,
        recorded_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'Asia/Jakarta'),
        UNIQUE (log_week_start, v_id)
    );
    SQL;
    }

    public static function createMonthlyRevenueLogTable()
    {
        return <<<SQL
    CREATE TABLE IF NOT EXISTS monthly_revenue_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        log_month CHAR(7) NOT NULL,
        v_id CHAR(36) NOT NULL,
        total_revenue DECIMAL(16,2) NOT NULL,
        recorded_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'Asia/Jakarta'),
        UNIQUE (log_month, v_id)
    );
    SQL;
    }

    public static function createUserPointsLogTable()
    {
        return <<<SQL
CREATE TABLE IF NOT EXISTS user_points_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    change_amount INT NOT NULL,
    event VARCHAR(60) NOT NULL,
    changed_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'Asia/Jakarta'),
    related_t_id CHAR(36) NOT NULL
);
SQL;
    }

    public static function createVendorEarningsLogTable()
    {
        return <<<SQL
    CREATE TABLE IF NOT EXISTS vendor_earnings_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        vendor_id CHAR(36) NOT NULL,
        amount DECIMAL(14,2) NOT NULL,
        recorded_at TIMESTAMP DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'Asia/Jakarta'),
        UNIQUE (vendor_id)
    );
    SQL;
    }
}
