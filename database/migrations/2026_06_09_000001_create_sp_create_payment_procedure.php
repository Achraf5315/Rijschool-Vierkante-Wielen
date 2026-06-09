<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_CreatePayment');

        DB::unprepared(<<<'SQL'
CREATE PROCEDURE sp_CreatePayment(
    IN p_InvoiceId INT,
    IN p_Amount DECIMAL(10,2),
    IN p_Method VARCHAR(20),
    IN p_TransactionRef VARCHAR(150),
    IN p_Notes VARCHAR(255)
)
BEGIN
    DECLARE v_invoice_exists INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    SELECT COUNT(*)
        INTO v_invoice_exists
    FROM Invoice
    WHERE Id = p_InvoiceId
      AND IsActive = 1;

    IF v_invoice_exists = 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Invoice not found';
    END IF;

    INSERT INTO Payment (
        InvoiceId,
        Amount,
        Method,
        TransactionRef,
        Status,
        IsActive,
        Notes,
        PaymentDate,
        CreatedAt,
        UpdatedAt
    ) VALUES (
        p_InvoiceId,
        p_Amount,
        p_Method,
        p_TransactionRef,
        'Pending',
        1,
        p_Notes,
        NOW(),
        NOW(),
        NOW()
    );

    UPDATE Invoice
    SET Status = 'Paid',
        UpdatedAt = NOW()
    WHERE Id = p_InvoiceId;

    COMMIT;

    SELECT LAST_INSERT_ID() AS PaymentId;
END
SQL);
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::unprepared('DROP PROCEDURE IF EXISTS sp_CreatePayment');
    }
};
