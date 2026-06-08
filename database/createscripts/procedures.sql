-- ============================================================
-- STORED PROCEDURES - Rijschool Vierkante Wielen
-- ============================================================

-- ============================================================
-- INSTRUCTEUR PROCEDURES
-- ============================================================

-- Get all instructors with vehicle info
DELIMITER $$
CREATE PROCEDURE sp_GetAllInstructors()
BEGIN
    SELECT 
        i.Id as InstructorID
        ,c.FirstName
        ,c.LastName
        ,c.Phone
        ,u.email as Email
        ,i.LicenseNumber
        ,i.IsActive
        ,v.LicensePlate
        ,v.Brand as VehicleBrand
        ,v.Model as VehicleModel
    FROM Instructor i
    INNER JOIN Contact c ON i.ContactId = c.Id
    INNER JOIN users u ON c.UserId = u.id
    LEFT JOIN InstructorVehicle iv ON i.Id = iv.InstructorId AND iv.IsActive = TRUE
    LEFT JOIN Vehicle v ON iv.VehicleId = v.Id AND v.IsActive = TRUE
    WHERE i.IsActive = TRUE;
END $$
DELIMITER ;

-- Get instructor by ID with vehicle info
DELIMITER $$
CREATE PROCEDURE sp_GetInstructorById(IN p_InstructorId INT)
BEGIN
    SELECT 
        i.Id as InstructorID
        ,c.FirstName
        ,c.LastName
        ,c.Phone
        ,u.email as Email
        ,i.LicenseNumber
        ,i.IsActive
        ,v.LicensePlate
        ,v.Brand as VehicleBrand
        ,v.Model as VehicleModel
    FROM Instructor i
    INNER JOIN Contact c ON i.ContactId = c.Id
    INNER JOIN users u ON c.UserId = u.id
    LEFT JOIN InstructorVehicle iv ON i.Id = iv.InstructorId AND iv.IsActive = TRUE
    LEFT JOIN Vehicle v ON iv.VehicleId = v.Id
    WHERE i.Id = p_InstructorId AND i.IsActive = TRUE;
END $$
DELIMITER ;

-- Update instructor
DELIMITER $$
CREATE PROCEDURE sp_UpdateInstructor(
    IN p_InstructorId INT
    ,IN p_FirstName VARCHAR(100)
    ,IN p_LastName VARCHAR(100)
    ,IN p_Phone VARCHAR(13)
    ,IN p_LicenseNumber VARCHAR(50)
    ,IN p_IsActive BOOLEAN
)
BEGIN
    DECLARE v_ContactId INT;
    
    -- Get contact ID
    SELECT ContactId INTO v_ContactId FROM Instructor WHERE Id = p_InstructorId;
    
    -- Update contact
    UPDATE Contact 
    SET FirstName = p_FirstName
        ,LastName = p_LastName
        ,Phone = p_Phone
        ,UpdatedAt = CURRENT_TIMESTAMP
    WHERE Id = v_ContactId;
    
    -- Update instructor
    UPDATE Instructor 
    SET LicenseNumber = p_LicenseNumber
        ,IsActive = p_IsActive
        ,UpdatedAt = CURRENT_TIMESTAMP
    WHERE Id = p_InstructorId;
END $$
DELIMITER ;

-- Delete instructor (soft delete)
DELIMITER $$
CREATE PROCEDURE sp_DeleteInstructor(IN p_InstructorId INT)
BEGIN
    DECLARE v_ContactId INT;
    
    SELECT ContactId INTO v_ContactId FROM Instructor WHERE Id = p_InstructorId;
    
    UPDATE Instructor SET IsActive = FALSE, UpdatedAt = CURRENT_TIMESTAMP WHERE Id = p_InstructorId;
    UPDATE Contact SET IsActive = FALSE, UpdatedAt = CURRENT_TIMESTAMP WHERE Id = v_ContactId;
END $$
DELIMITER ;

-- ============================================================
-- VEHICLE (AUTO) PROCEDURES
-- ============================================================

-- Get all vehicles
DELIMITER $$
CREATE PROCEDURE sp_GetAllVehicles()
BEGIN
    SELECT 
        v.Id as VehicleID
        ,v.LicensePlate
        ,v.Brand
        ,v.Model
        ,v.Year
        ,v.IsActive as IsAvailable
        ,i.Id as InstructorID
        ,c.FirstName as InstructorFirstName
        ,c.LastName as InstructorLastName
        ,CONCAT(c.FirstName, ' ', c.LastName) as InstructorName
    FROM Vehicle v
    LEFT JOIN InstructorVehicle iv ON v.Id = iv.VehicleId AND iv.IsActive = TRUE
    LEFT JOIN Instructor i ON iv.InstructorId = i.Id
    LEFT JOIN Contact c ON i.ContactId = c.Id
    WHERE v.IsActive = TRUE
    ORDER BY v.LicensePlate;
END $$
DELIMITER ;

-- Get vehicle by ID
DELIMITER $$
CREATE PROCEDURE sp_GetVehicleById(IN p_VehicleId INT)
BEGIN
    SELECT 
        v.Id as VehicleID
        ,v.LicensePlate
        ,v.Brand
        ,v.Model
        ,v.Year
        ,v.IsActive as IsAvailable
        ,i.Id as InstructorID
        ,CONCAT(c.FirstName, ' ', c.LastName) as InstructorName
    FROM Vehicle v
    LEFT JOIN InstructorVehicle iv ON v.Id = iv.VehicleId AND iv.IsActive = TRUE
    LEFT JOIN Instructor i ON iv.InstructorId = i.Id
    LEFT JOIN Contact c ON i.ContactId = c.Id
    WHERE v.Id = p_VehicleId AND v.IsActive = TRUE;
END $$
DELIMITER ;

-- Update vehicle
DELIMITER $$
CREATE PROCEDURE sp_UpdateVehicle(
    IN p_VehicleId INT
    ,IN p_LicensePlate VARCHAR(20)
    ,IN p_Brand VARCHAR(50)
    ,IN p_Model VARCHAR(50)
    ,IN p_Year INT
    ,IN p_IsAvailable BOOLEAN
)
BEGIN
    UPDATE Vehicle 
    SET LicensePlate = p_LicensePlate
        ,Brand = p_Brand
        ,Model = p_Model
        ,Year = p_Year
        ,IsActive = p_IsAvailable
        ,UpdatedAt = CURRENT_TIMESTAMP
    WHERE Id = p_VehicleId;
END $$
DELIMITER ;

-- Delete vehicle (soft delete)
DELIMITER $$
CREATE PROCEDURE sp_DeleteVehicle(IN p_VehicleId INT)
BEGIN
    UPDATE Vehicle SET IsActive = FALSE, UpdatedAt = CURRENT_TIMESTAMP WHERE Id = p_VehicleId;
END $$
DELIMITER ;

-- ============================================================
-- CLIENT (LEERLING) PROCEDURES
-- ============================================================

-- Get all clients
DELIMITER $$
CREATE PROCEDURE sp_GetAllClients()
BEGIN
    SELECT 
        cli.Id as ClientID
        ,c.FirstName
        ,c.LastName
        ,c.Phone
        ,u.email as Email
        ,cli.LicenseCategory
        ,cli.IsActive
        ,COUNT(cp.Id) as PackagesCount
    FROM Client cli
    INNER JOIN Contact c ON cli.ContactId = c.Id
    INNER JOIN users u ON c.UserId = u.id
    LEFT JOIN ClientPackage cp ON cli.Id = cp.ClientId
    WHERE cli.IsActive = TRUE
    GROUP BY cli.Id
    ORDER BY c.LastName, c.FirstName;
END $$
DELIMITER ;

-- Get client by ID with packages
DELIMITER $$
CREATE PROCEDURE sp_GetClientById(IN p_ClientId INT)
BEGIN
    SELECT 
        cli.Id as ClientID
        ,c.FirstName
        ,c.LastName
        ,c.Phone
        ,u.email as Email
        ,cli.LicenseCategory
        ,cli.IsActive
        ,dp.Id as PackageID
        ,dp.Name as PackageName
        ,cp.LessonsUsed
        ,dp.LessonCount
    FROM Client cli
    INNER JOIN Contact c ON cli.ContactId = c.Id
    INNER JOIN users u ON c.UserId = u.id
    LEFT JOIN ClientPackage cp ON cli.Id = cp.ClientId
    LEFT JOIN DrivingPackage dp ON cp.PackageId = dp.Id
    WHERE cli.Id = p_ClientId AND cli.IsActive = TRUE;
END $$
DELIMITER ;

-- ============================================================
-- LESSON PROCEDURES
-- ============================================================

-- Get lessons by instructor
DELIMITER $$
CREATE PROCEDURE sp_GetLessonsByInstructor(
    IN p_InstructorId INT
    ,IN p_StartDate DATE
    ,IN p_EndDate DATE
)
BEGIN
    SELECT 
        l.Id as LessonID
        ,l.StartTime
        ,l.EndTime
        ,l.Status
        ,c.FirstName as ClientFirstName
        ,c.LastName as ClientLastName
        ,v.LicensePlate
        ,v.Brand as VehicleBrand
        ,v.Model as VehicleModel
    FROM Lesson l
    INNER JOIN Client cli ON l.ClientId = cli.Id
    INNER JOIN Contact c ON cli.ContactId = c.Id
    INNER JOIN Vehicle v ON l.VehicleId = v.Id
    WHERE l.InstructorId = p_InstructorId
        AND DATE(l.StartTime) BETWEEN p_StartDate AND p_EndDate
        AND l.IsActive = TRUE
    ORDER BY l.StartTime;
END $$
DELIMITER ;

-- Get available time slots for instructor
DELIMITER $$
CREATE PROCEDURE sp_GetInstructorAvailableSlots(
    IN p_InstructorId INT
    ,IN p_Date DATE
)
BEGIN
    SELECT 
        ia.Id
        ,ia.StartTime
        ,ia.EndTime
        ,ia.DayOfWeek
    FROM InstructorAvailability ia
    WHERE ia.InstructorId = p_InstructorId
        AND ia.IsActive = TRUE
        AND (ia.IsRecurring = TRUE OR ia.SpecificDate = p_Date);
END $$
DELIMITER ;

-- ============================================================
-- INVOICE PROCEDURES
-- ============================================================

-- Get invoices by client
DELIMITER $$
CREATE PROCEDURE sp_GetInvoicesByClient(IN p_ClientId INT)
BEGIN
    SELECT 
        inv.Id as InvoiceID
        ,inv.InvoiceNumber
        ,inv.IssueDate
        ,inv.DueDate
        ,inv.TotalAmount
        ,inv.Status
        ,c.FirstName as ClientFirstName
        ,c.LastName as ClientLastName
    FROM Invoice inv
    INNER JOIN Client cli ON inv.ClientId = cli.Id
    INNER JOIN Contact c ON cli.ContactId = c.Id
    WHERE inv.ClientId = p_ClientId AND inv.IsActive = TRUE
    ORDER BY inv.IssueDate DESC;
END $$
DELIMITER ;

-- Get invoices by status
DELIMITER $$
CREATE PROCEDURE sp_GetInvoicesByStatus(IN p_Status VARCHAR(20))
BEGIN
    SELECT 
        inv.Id as InvoiceID
        ,inv.InvoiceNumber
        ,inv.IssueDate
        ,inv.DueDate
        ,inv.TotalAmount
        ,inv.Status
        ,c.FirstName as ClientFirstName
        ,c.LastName as ClientLastName
    FROM Invoice inv
    INNER JOIN Client cli ON inv.ClientId = cli.Id
    INNER JOIN Contact c ON cli.ContactId = c.Id
    WHERE inv.Status = p_Status AND inv.IsActive = TRUE
    ORDER BY inv.DueDate;
END $$
DELIMITER ;

-- ============================================================
-- REPORTING PROCEDURES
-- ============================================================

-- Get instructor revenue
DELIMITER $$
CREATE PROCEDURE sp_GetInstructorRevenue(
    IN p_InstructorId INT
    ,IN p_StartDate DATE
    ,IN p_EndDate DATE
)
BEGIN
    SELECT 
        i.Id as InstructorID
        ,c.FirstName
        ,c.LastName
        ,COUNT(DISTINCT l.Id) as TotalLessons
        ,SUM(CASE WHEN l.Status = 'Completed' THEN 1 ELSE 0 END) as CompletedLessons
        ,COALESCE(SUM(il.LineTotal), 0) as TotalRevenue
    FROM Instructor i
    INNER JOIN Contact c ON i.ContactId = c.Id
    LEFT JOIN Lesson l ON i.Id = l.InstructorId 
        AND DATE(l.StartTime) BETWEEN p_StartDate AND p_EndDate
    LEFT JOIN InvoiceLine il ON l.Id = il.LessonId
    WHERE i.Id = p_InstructorId AND i.IsActive = TRUE
    GROUP BY i.Id;
END $$
DELIMITER ;

-- Get vehicle usage statistics
DELIMITER $$
CREATE PROCEDURE sp_GetVehicleUsageStats(
    IN p_VehicleId INT
    ,IN p_StartDate DATE
    ,IN p_EndDate DATE
)
BEGIN
    SELECT 
        v.Id as VehicleID
        ,v.LicensePlate
        ,v.Brand
        ,v.Model
        ,COUNT(DISTINCT l.Id) as TotalLessons
        ,COUNT(DISTINCT l.InstructorId) as UniqueInstructors
        ,COALESCE(SUM(TIMESTAMPDIFF(MINUTE, l.StartTime, l.EndTime)), 0) as TotalMinutes
    FROM Vehicle v
    LEFT JOIN Lesson l ON v.Id = l.VehicleId 
        AND DATE(l.StartTime) BETWEEN p_StartDate AND p_EndDate
    WHERE v.Id = p_VehicleId AND v.IsActive = TRUE
    GROUP BY v.Id;
END $$
DELIMITER ;
