DROP DATABASE IF EXISTS VierkantenWielen;

CREATE DATABASE VierkantenWielen;

USE VierkantenWielen;
-- ============================================================
-- Rijschool Database Script
-- Gebaseerd op ERD structuur + gebruikersrollen en activiteiten
-- ============================================================

-- ============================================================
-- STAP 1: BASIS INFRASTRUCTUUR (Address, User, Contact)
-- ============================================================

CREATE TABLE Address (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,Street         VARCHAR(55)     NOT NULL
    ,HouseNumber    VARCHAR(5)      NOT NULL
    ,PostalCode     VARCHAR(6)      NOT NULL
    ,City           VARCHAR(30)     NOT NULL
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE Contact (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,FirstName      VARCHAR(100)    NOT NULL
    ,LastName       VARCHAR(100)    NOT NULL
    ,Phone          VARCHAR(13)     NOT NULL
    ,DateOfBirth    DATE            NOT NULL
    ,AddressId      INT             NOT NULL
    ,UserId         INT             NOT NULL UNIQUE
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_contact_address FOREIGN KEY (AddressId) REFERENCES Address(Id)
    ,CONSTRAINT fk_contact_user    FOREIGN KEY (UserId)    REFERENCES users(id)
);

-- ============================================================
-- STAP 2: LEERLING (Client / Student)
-- ============================================================

CREATE TABLE Client (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,ContactId      INT             NOT NULL
    ,BSN            VARCHAR(9)      UNIQUE
    ,LicenseCategory VARCHAR(10)   NOT NULL DEFAULT 'B'
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_client_contact FOREIGN KEY (ContactId) REFERENCES Contact(Id)
);

-- ============================================================
-- STAP 3: INSTRUCTEUR
-- ============================================================

CREATE TABLE Instructor (
    Id                  INT PRIMARY KEY AUTO_INCREMENT
    ,ContactId          INT             NOT NULL
    ,LicenseNumber      VARCHAR(50)     NOT NULL UNIQUE
    ,Certification      VARCHAR(100),   NOT NULL
    ,MaxStudentsPerDay  INT             NOT NULL DEFAULT 8
    ,HourlyRate         DECIMAL(2,2)    NOT NULL
    ,IsActive           BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes              VARCHAR(255)    NULL
    ,CreatedAt          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_instructor_user    FOREIGN KEY (UserId)    REFERENCES users(id)
    ,CONSTRAINT fk_instructor_address FOREIGN KEY (AddressId) REFERENCES Address(Id)
);

-- ============================================================
-- STAP 4: AUTO (Vehicle)
-- ============================================================

CREATE TABLE Vehicle (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,LicensePlate   VARCHAR(20)     NOT NULL UNIQUE
    ,Brand          VARCHAR(50)     NOT NULL
    ,Model          VARCHAR(50)     NOT NULL
    ,Year           INT             NOT NULL
    ,Transmission   ENUM('Manual','Automatic') NOT NULL DEFAULT 'Manual'
    ,Category       VARCHAR(10)     NOT NULL DEFAULT 'B'
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,APKExpiry       DATE,          NOT NULL
    ,InsuranceExpiry DATE,          NOT NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Koppeling instructeur <-> auto
CREATE TABLE InstructorVehicle (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,InstructorId   INT             NOT NULL
    ,VehicleId      INT             NOT NULL
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,AssignedAt     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_iv_instructor FOREIGN KEY (InstructorId) REFERENCES Instructor(Id)
    ,CONSTRAINT fk_iv_vehicle    FOREIGN KEY (VehicleId)    REFERENCES Vehicle(Id)
);

-- ============================================================
-- STAP 5: LESRIJPAKKETTEN (DrivingPackage)
-- ============================================================

CREATE TABLE DrivingPackage (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,Name           VARCHAR(150)    NOT NULL
    ,Description    VARCHAR(255)    NOT NULL
    ,LessonCount    INT             NOT NULL
    ,LessonDuration INT             NOT NULL DEFAULT 60
    ,Price          DECIMAL(10,2)   NOT NULL
    ,Category       VARCHAR(10)     NOT NULL DEFAULT 'B'
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Leerling koopt pakket
CREATE TABLE ClientPackage (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,ClientId       INT             NOT NULL
    ,PackageId      INT             NOT NULL
    ,LessonsUsed    INT             NOT NULL DEFAULT 0
    ,IsCompleted    BOOLEAN         NOT NULL DEFAULT FALSE
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,PurchasedAt    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_cp_client  FOREIGN KEY (ClientId)  REFERENCES Client(Id)
    ,CONSTRAINT fk_cp_package FOREIGN KEY (PackageId) REFERENCES DrivingPackage(Id)
);

-- ============================================================
-- STAP 6: RIJLES PLANNING (Lesson / Schedule)
-- ============================================================

CREATE TABLE Lesson (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,ClientId       INT             NOT NULL
    ,InstructorId   INT             NOT NULL
    ,VehicleId      INT             NOT NULL
    ,ClientPackageId INT            NULL
    ,StartTime      DATETIME        NOT NULL
    ,EndTime        DATETIME        NOT NULL
    ,Location       VARCHAR(255),
    ,Status         ENUM('Open','Planned','Confirmed','InProgress','Completed','CancelledByClient','CancelledByInstructor') NOT NULL DEFAULT 'Open'
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_lesson_client      FOREIGN KEY (ClientId)        REFERENCES Client(Id)
    ,CONSTRAINT fk_lesson_instructor  FOREIGN KEY (InstructorId)    REFERENCES Instructor(Id)
    ,CONSTRAINT fk_lesson_vehicle     FOREIGN KEY (VehicleId)       REFERENCES Vehicle(Id)
    ,CONSTRAINT fk_lesson_cp          FOREIGN KEY (ClientPackageId) REFERENCES ClientPackage(Id)
);

-- ============================================================
-- STAP 7: FACTURATIE (Invoice)
-- ============================================================

CREATE TABLE Invoice (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,InvoiceNumber  VARCHAR(50)     NOT NULL UNIQUE
    ,ClientId       INT             NOT NULL
    ,InstructorId   INT,            NOT NULL
    ,IssueDate      DATE            NOT NULL DEFAULT (CURRENT_DATE)
    ,DueDate        DATE            NOT NULL
    ,Subtotal       DECIMAL(10,2)   NOT NULL
    ,VATRate        DECIMAL(5,2)    NOT NULL DEFAULT 21.00
    ,VATAmount      DECIMAL(4,2)    NOT NULL
    ,TotalAmount    DECIMAL(5,2)    NOT NULL
    ,Status         ENUM('Draft','Sent','Paid','Overdue','Cancelled') NOT NULL DEFAULT 'Draft'
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_invoice_client     FOREIGN KEY (ClientId)     REFERENCES Client(Id)
    ,CONSTRAINT fk_invoice_instructor FOREIGN KEY (InstructorId) REFERENCES Instructor(Id)
);

-- Factuurregels
CREATE TABLE InvoiceLine (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,InvoiceId      INT             NOT NULL
    ,Description    VARCHAR(255)    NOT NULL
    ,Quantity       INT             NOT NULL DEFAULT 1
    ,UnitPrice      DECIMAL(10,2)   NOT NULL
    ,LineTotal      DECIMAL(10,2)   NOT NULL
    ,LessonId       INT             NOT NULL
    ,PackageId      INT             NOT NULL
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_il_invoice FOREIGN KEY (InvoiceId) REFERENCES Invoice(Id)
    ,CONSTRAINT fk_il_lesson  FOREIGN KEY (LessonId)  REFERENCES Lesson(Id)
    ,CONSTRAINT fk_il_package FOREIGN KEY (PackageId) REFERENCES DrivingPackage(Id)
);

-- ============================================================
-- STAP 8: BETALING (Payment)
-- ============================================================

CREATE TABLE Payment (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,InvoiceId      INT             NOT NULL
    ,Amount         DECIMAL(10,2)   NOT NULL
    ,Method         ENUM('iDEAL','CreditCard','BankTransfer','Cash','Tikkie') NOT NULL
    ,TransactionRef VARCHAR(150) NOT NULL
    ,Status         ENUM('Pending','Completed','Failed','Refunded') NOT NULL DEFAULT 'Pending'
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,PaymentDate    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_payment_invoice FOREIGN KEY (InvoiceId) REFERENCES Invoice(Id)
);

-- ============================================================
-- STAP 9: MELDINGEN (Notification)
-- ============================================================

CREATE TABLE Notification (
    Id          INT PRIMARY KEY AUTO_INCREMENT
    ,UserId     INT             NOT NULL
    ,Type       ENUM('LessonReminder','LessonConfirmed','LessonCancelled',
                     'InvoiceGenerated','PaymentReceived','SystemMessage',
                     'PackageLowBalance') NOT NULL
    ,Title      VARCHAR(150)    NOT NULL
    ,Message    VARCHAR(255)    NOT NULL
    ,IsRead     BOOLEAN         NOT NULL DEFAULT FALSE
    ,IsActive   BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes      VARCHAR(255)    NULL
    ,SentAt     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,ReadAt     DATETIME        NULL
    ,CreatedAt  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_notification_user FOREIGN KEY (UserId) REFERENCES users(id)
);

-- CBR Examen aanvraag
CREATE TABLE ExamRequest (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,ClientId       INT             NOT NULL
    ,InstructorId   INT             NOT NULL
    ,RequestedDate  DATE            NOT NULL
    ,ExamDate       DATE            NOT NULL
    ,ExamLocation   VARCHAR(255)    NOT NULL
    ,ExamType       ENUM('Theory','Practical') NOT NULL
    ,Status         ENUM('Requested','Scheduled','Passed','Failed','Withdrawn') NOT NULL DEFAULT 'Requested'
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_exam_client     FOREIGN KEY (ClientId)     REFERENCES Client(Id),
    ,CONSTRAINT fk_exam_instructor FOREIGN KEY (InstructorId) REFERENCES Instructor(Id)
);

-- ============================================================
-- STAP 11: BESCHIKBAARHEID INSTRUCTEUR
-- ============================================================

CREATE TABLE InstructorAvailability (
    Id              INT PRIMARY KEY AUTO_INCREMENT
    ,InstructorId   INT             NOT NULL
    ,DayOfWeek      TINYINT         NOT NULL CHECK (DayOfWeek BETWEEN 1 AND 7)
    ,StartTime      TIME            NOT NULL
    ,EndTime        TIME            NOT NULL
    ,IsRecurring    BOOLEAN         NOT NULL DEFAULT TRUE
    ,SpecificDate   DATE            NOT NULL
    ,IsActive       BOOLEAN         NOT NULL DEFAULT TRUE
    ,Notes          VARCHAR(255)    NULL
    ,CreatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
    ,UpdatedAt      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ,CONSTRAINT fk_avail_instructor FOREIGN KEY (InstructorId) REFERENCES Instructor(Id)
);

-- ============================================================
-- INDEXEN voor performance
-- ============================================================

CREATE INDEX idx_lesson_starttime     ON Lesson(StartTime);
CREATE INDEX idx_lesson_client        ON Lesson(ClientId);
CREATE INDEX idx_lesson_instructor    ON Lesson(InstructorId);
CREATE INDEX idx_invoice_client       ON Invoice(ClientId);
CREATE INDEX idx_invoice_status       ON Invoice(Status);
CREATE INDEX idx_payment_invoice      ON Payment(InvoiceId);
CREATE INDEX idx_notification_user    ON Notification(UserId, IsRead);
CREATE INDEX idx_user_email           ON User(Email);
CREATE INDEX idx_contact_user         ON Contact(UserId);