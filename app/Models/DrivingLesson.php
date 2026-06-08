<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PDO;

class DrivingLesson extends Model
{
    protected $table = 'Lesson';

    protected $primaryKey = 'Id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = true;

    public const CREATED_AT = 'CreatedAt';

    public const UPDATED_AT = 'UpdatedAt';

    protected $guarded = ['Id'];

    /**
     * Get the raw PDO connection that is used for stored procedures.
     */
    private static function connection(): PDO
    {
        return DB::connection()->getPdo();
    }

    /**
     * Fetch all active driving lessons from the stored procedure.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAllDrivingLessons(): array
    {
        $statement = self::connection()->prepare('CALL sp_GetAllDrivingLessons()');
        $statement->execute();

        $lessons = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $lessons;
    }

    /**
     * Fetch all active clients for the lesson form.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getActiveClients(): array
    {
        $sql = <<<'SQL'
            SELECT
                c.Id,
                CONCAT(contact.FirstName, ' ', contact.LastName) AS FullName
            FROM Client c
            INNER JOIN Contact contact ON contact.Id = c.ContactId
            WHERE c.IsActive = 1
              AND contact.IsActive = 1
            ORDER BY contact.FirstName, contact.LastName
        SQL;

        $statement = self::connection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch all active instructors for the lesson form.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getActiveInstructors(): array
    {
        $sql = <<<'SQL'
            SELECT
                i.Id,
                CONCAT(contact.FirstName, ' ', contact.LastName) AS FullName
            FROM Instructor i
            INNER JOIN Contact contact ON contact.Id = i.ContactId
            WHERE i.IsActive = 1
              AND contact.IsActive = 1
            ORDER BY contact.FirstName, contact.LastName
        SQL;

        $statement = self::connection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch all active vehicles for the lesson form.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getActiveVehicles(): array
    {
        $sql = <<<'SQL'
            SELECT
                Id,
                CONCAT(Brand, ' ', Model, ' - ', LicensePlate) AS FullName
            FROM Vehicle
            WHERE IsActive = 1
            ORDER BY Brand, Model, LicensePlate
        SQL;

        $statement = self::connection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insert a new driving lesson through the stored procedure.
     *
     * @param array<string, mixed> $data
     * @return int
     */
    public function addDrivingLesson(array $data): int
    {
        $statement = self::connection()->prepare(
            'CALL sp_AddDrivingLesson(:clientId, :instructorId, :vehicleId, :startTime, :endTime, :location, :notes)'
        );

        $statement->execute([
            ':clientId' => $data['client_id'],
            ':instructorId' => $data['instructor_id'],
            ':vehicleId' => $data['vehicle_id'],
            ':startTime' => $data['start_time'],
            ':endTime' => $data['end_time'],
            ':location' => $data['location'],
            ':notes' => $data['notes'],
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return (int) ($result['InsertedId'] ?? 0);
    }
}