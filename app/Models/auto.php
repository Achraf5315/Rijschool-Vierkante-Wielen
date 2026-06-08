<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
 
class Auto extends Model
{
    // Map model to the Vehicle table
    protected $table = 'Vehicle';
 
    // Primary key column name
    protected $primaryKey = 'VehicleID';
 
    // Mass-assignable columns
    protected $fillable = [
        'LicensePlate',
        'Brand',
        'Model',
        'Year',
        'IsAvailable',
    ];
 
    // Retrieve all vehicles with their linked instructor (if any)
    public static function GetAllAutos(): array
    {
        return DB::select("
            SELECT
                v.VehicleID,
                v.LicensePlate,
                v.Brand,
                v.Model,
                v.Year,
                v.IsAvailable,
                CONCAT(c.FirstName, ' ', c.LastName) AS InstructorName
            FROM Vehicle v
            LEFT JOIN InstructorVehicle iv ON iv.VehicleID = v.VehicleID
            LEFT JOIN Instructor i ON i.InstructorID = iv.InstructorID
            LEFT JOIN Contact c ON c.ContactID = i.ContactID
            ORDER BY v.Brand ASC, v.Model ASC
        ");
    }
 
    // Retrieve a single vehicle by its ID
    public static function GetAutoById(int $id): ?object
    {
        $result = DB::select("
            SELECT
                v.VehicleID,
                v.LicensePlate,
                v.Brand,
                v.Model,
                v.Year,
                v.IsAvailable
            FROM Vehicle v
            WHERE v.VehicleID = ?
            LIMIT 1
        ", [$id]);
 
        return $result[0] ?? null;
    }
 
    // Insert a new vehicle and return its new ID
    public static function CreateAuto(array $data): int
    {
        DB::insert("
            INSERT INTO Vehicle (LicensePlate, Brand, Model, Year, IsAvailable)
            VALUES (?, ?, ?, ?, 1)
        ", [
            $data['kenteken'],
            $data['merk'],
            $data['model'],
            $data['bouwjaar'],
        ]);
 
        return (int) DB::getPdo()->lastInsertId();
    }
 
    // Update an existing vehicle by ID
    public static function UpdateAuto(int $id, array $data): void
    {
        DB::update("
            UPDATE Vehicle
            SET LicensePlate = ?,
                Brand        = ?,
                Model        = ?,
                Year         = ?,
                IsAvailable  = ?
            WHERE VehicleID = ?
        ", [
            $data['kenteken'],
            $data['merk'],
            $data['model'],
            $data['bouwjaar'],
            $data['is_available'] ?? 1,
            $id,
        ]);
    }
 
    // Soft-delete by marking unavailable, or hard-delete
    public static function DeleteAuto(int $id): void
    {
        DB::delete("DELETE FROM Vehicle WHERE VehicleID = ?", [$id]);
    }
}
 