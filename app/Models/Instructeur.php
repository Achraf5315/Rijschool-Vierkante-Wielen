<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
 
class Instructeur extends Model
{
    // Map model to the Instructor table
    protected $table = 'Instructor';
 
    // Primary key column name
    protected $primaryKey = 'InstructorID';
 
    // Mass-assignable columns
    protected $fillable = [
        'ContactID',
        'LicenseNumber',
        'IsActive',
    ];
 
    // Retrieve all instructors with contact info and their linked vehicle
    public static function GetAllInstructeurs(): array
    {
        return DB::select("
            SELECT
                i.InstructorID,
                i.LicenseNumber,
                i.IsActive,
                c.FirstName,
                c.LastName,
                c.Email,
                c.Phone,
                v.Brand       AS VehicleBrand,
                v.Model       AS VehicleModel,
                v.LicensePlate
            FROM Instructor i
            INNER JOIN Contact c ON c.ContactID = i.ContactID
            LEFT JOIN InstructorVehicle iv ON iv.InstructorID = i.InstructorID
            LEFT JOIN Vehicle v ON v.VehicleID = iv.VehicleID
            ORDER BY c.LastName ASC, c.FirstName ASC
        ");
    }
 
    // Retrieve a single instructor by ID
    public static function GetInstructeurById(int $id): ?object
    {
        $result = DB::select("
            SELECT
                i.InstructorID,
                i.LicenseNumber,
                i.IsActive,
                c.ContactID,
                c.FirstName,
                c.LastName,
                c.Email,
                c.Phone
            FROM Instructor i
            INNER JOIN Contact c ON c.ContactID = i.ContactID
            WHERE i.InstructorID = ?
            LIMIT 1
        ", [$id]);
 
        return $result[0] ?? null;
    }
 
    // Insert a new contact row and return its ID
    public static function CreateContact(array $data): int
    {
        DB::insert("
            INSERT INTO Contact (FirstName, LastName, Email, Phone)
            VALUES (?, ?, ?, ?)
        ", [
            $data['voornaam'],
            $data['achternaam'],
            $data['email'],
            $data['telefoon'],
        ]);
 
        return (int) DB::getPdo()->lastInsertId();
    }
 
    // Insert a new instructor row linked to a contact
    public static function CreateInstructeur(int $contactId, array $data): int
    {
        DB::insert("
            INSERT INTO Instructor (ContactID, LicenseNumber, IsActive)
            VALUES (?, ?, 1)
        ", [
            $contactId,
            $data['rijbewijsnummer'],
        ]);
 
        return (int) DB::getPdo()->lastInsertId();
    }
 
    // Update contact and instructor rows in one call
    public static function UpdateInstructeur(int $id, array $data): void
    {
        // Update the linked contact row first
        DB::update("
            UPDATE Contact c
            INNER JOIN Instructor i ON i.ContactID = c.ContactID
            SET c.FirstName = ?,
                c.LastName  = ?,
                c.Email     = ?,
                c.Phone     = ?
            WHERE i.InstructorID = ?
        ", [
            $data['voornaam'],
            $data['achternaam'],
            $data['email'],
            $data['telefoon'],
            $id,
        ]);
 
        // Update the instructor-specific fields
        DB::update("
            UPDATE Instructor
            SET LicenseNumber = ?,
                IsActive      = ?
            WHERE InstructorID = ?
        ", [
            $data['rijbewijsnummer'],
            $data['is_active'] ?? 1,
            $id,
        ]);
    }
 
    // Delete an instructor and their contact record
    public static function DeleteInstructeur(int $id): void
    {
        // Remove InstructorVehicle links first to respect FK constraints
        DB::delete("DELETE FROM InstructorVehicle WHERE InstructorID = ?", [$id]);
 
        // Get contactID before deleting instructor
        $row = DB::select("SELECT ContactID FROM Instructor WHERE InstructorID = ?", [$id]);
        $contactId = $row[0]->ContactID ?? null;
 
        DB::delete("DELETE FROM Instructor WHERE InstructorID = ?", [$id]);
 
        if ($contactId) {
            DB::delete("DELETE FROM Contact WHERE ContactID = ?", [$contactId]);
        }
    }
}