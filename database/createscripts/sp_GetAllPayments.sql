DROP PROCEDURE IF EXISTS sp_GetAllPayments;

DELIMITER $$

CREATE PROCEDURE sp_GetAllPayments()
BEGIN
    SELECT
        p.Id AS PaymentId,
        p.InvoiceId,
        i.InvoiceNumber,
        p.Amount,
        p.Method,
        p.TransactionRef,
        p.Status,
        p.IsActive
    FROM Payment p
    INNER JOIN Invoice i ON p.InvoiceId = i.Id
    WHERE i.IsActive = 1;
END $$

DELIMITER ;

CALL sp_GetAllPayments();