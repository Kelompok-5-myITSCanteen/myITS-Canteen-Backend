-- Query Search 
-- 1. Mendapatkan menu berdasarkan ID kantin :
SELECT * from menus
NATURAL JOIN vendors 
NATURAL JOIN canteens 
WHERE vendors.k_id = :canteen_id;

-- 2. Mendapatkan menu berdasarkan nama vendor dari sisi pembeli :
SELECT * FROM menus
NATURAL JOIN vendors 
WHERE vendors.v_name = :vendor_name;

-- Query View
-- 3. Menampilkan histori pemesanan untuk penjual 
CREATE OR REPLACE VIEW vendor_purchased_menus_view AS (
    SELECT v.v_id, SUM(td.td_quantity) AS total_purchased
    FROM transaction_details td
    NATURAL JOIN menus m
    NATURAL JOIN vendors v
    NATURAL JOIN transactions t 
    WHERE DATE(t.t_time) = CURRENT_DATE
    GROUP BY v.v_id
);

-- 4. Mendapatkan menu berdasarkan ID vendor
CREATE OR REPLACE VIEW menuBy_vendorID as (
    SELECT * FROM menus
    WHERE v_id = :vendorId
);

-- Query Trigger
-- 5. Pengurangan stok menu setelah transaksi dikonfirmasi :
CREATE TRIGGER tr_after_transaction_reduce_stock
    AFTER UPDATE ON transactions
    FOR EACH ROW
    BEGIN
        IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' THEN
            CALL proc_reduce_stock(NEW.t_id);
        END IF;
    END;
    SQL;

-- 6. Penambahan pendapatan penjual setelah transaksi selesai :
CREATE TRIGGER tr_after_transaction_vendor_earnings
    AFTER UPDATE ON transactions
    FOR EACH ROW
    BEGIN
        IF OLD.t_status <> 'Selesai' AND NEW.t_status = 'Selesai' THEN
            CALL proc_add_vendor_earnings(NEW.t_id);
        END IF;
    END;
    SQL;

-- Query Procedure
-- 7. Pengurangan stok menu berdasarkan transaksi
CREATE PROCEDURE proc_reduce_stock(IN p_t_id CHAR(36))
    BEGIN
        UPDATE menus m
        JOIN transaction_details td ON td.m_id = m.m_id
        SET m.m_stock = m.m_stock - td.td_quantity
        WHERE td.t_id = p_t_id;
    END;
    SQL;

-- 8. Penambahan pendapatan penjual berdasarkan transaksi :
CREATE TABLE IF NOT EXISTS vendor_earnings_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            vendor_id CHAR(36) NOT NULL,
            amount DECIMAL(14,2) NOT NULL,
            recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (vendor_id)
        );

CREATE PROCEDURE proc_add_vendor_earnings(IN p_t_id CHAR(36))
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