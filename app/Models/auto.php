<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
 
class Auto extends Model
{
    // Map model to the Vehicle table
    protected $table = 'Vehicle';
 
    // Primary key column name
    protected $primaryKey = 'Id';
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
        v.Id,
        v.LicensePlate,
        v.Brand,
        v.Model,
        v.Year,
        v.IsActive,
        CONCAT(c.FirstName, ' ', c.LastName) AS InstructorName
    FROM Vehicle v
    LEFT JOIN InstructorVehicle iv ON iv.VehicleId = v.Id
    LEFT JOIN Instructor i ON i.Id = iv.InstructorId
    LEFT JOIN Contact c ON c.Id = i.ContactId
    ORDER BY v.Brand ASC, v.Model ASC
");
    }
 
    // Retrieve a single vehicle by its ID
    public static function GetAutoById(int $id): ?object
    {
        $result = DB::select("
            SELECT
                v.Id,
                v.LicensePlate,
                v.Brand,
                v.Model,
                v.Year,
                v.IsActive,
            FROM Vehicle v
            WHERE v.Id = ?
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
                IsActive     = ?
            WHERE Id = ?
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
   DB::delete("DELETE FROM Vehicle WHERE Id = ?", [$id]);
    }
}
 