<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            // Stored procedures are only supported on MySQL/MariaDB.
            // Skip creating/dropping procedures when using SQLite (tests use in-memory DB).
            return;
        }

        DB::statement('DROP PROCEDURE IF EXISTS sp_PakBoekingenAantal');
        DB::statement('DROP PROCEDURE IF EXISTS sp_PakAlleFacturen');
        DB::statement('DROP PROCEDURE IF EXISTS sp_MeestVoorkomendReis');
        DB::statement('DROP PROCEDURE IF EXISTS sp_PakAllePassagiers');
        DB::statement('DROP PROCEDURE IF EXISTS sp_PakFactuurBijId');
        DB::statement('DROP PROCEDURE IF EXISTS sp_WijzigFactuur');
        DB::statement('DROP PROCEDURE IF EXISTS sp_AnnuleerFactuur');


        DB::statement("
        CREATE PROCEDURE sp_PakBoekingenAantal()
        BEGIN
            SELECT COUNT(*) AS count
            FROM Boeking
            WHERE Boekingsstatus = 'Bevestigd';
        END
    ");
        DB::statement("
        CREATE PROCEDURE sp_PakAlleFacturen()
        BEGIN
            SELECT 
            f.Id
            ,b.Boekingsnummer
            ,CONCAT_WS(' ', pe.Voornaam, NULLIF(pe.Tussenvoegsel, ''), pe.Achternaam) AS PassagierNaam
            ,f.Factuurnummer
            ,f.Factuurdatum
            ,f.Factuurtijd
            ,f.TotaalBedrag
            ,f.Betaalstatus
            ,f.Betaalmethode
            ,f.IsActief
            ,f.Opmerking
            ,f.Datumaangemaakt
            ,f.Datumgewijzigd
            FROM Factuur f
            INNER JOIN Boeking b ON f.BoekingId = b.Id
            INNER JOIN Passagier p ON f.PassagierId = p.Id
            INNER JOIN Persoon pe ON p.PersoonId = pe.Id
            WHERE f.IsActief = 1
            ORDER BY f.Factuurdatum DESC;
        END
    ");
        DB::statement("
        CREATE PROCEDURE sp_MeestVoorkomendReis()
        BEGIN
            SELECT 
                ver.Luchthaven AS VertrekLuchthaven, 
                ver.Land AS VertrekLand,
                bes.Luchthaven AS BestemmingLuchthaven, 
                bes.Land AS BestemmingLand,
                COUNT(boe.Id) AS AantalBoekingen
            FROM Boeking boe
            JOIN Vlucht v ON boe.VluchtId = v.Id
            JOIN Bestemming bes ON v.BestemmingId = bes.Id
            JOIN Vertrek ver ON v.VertrekId = ver.Id
            WHERE boe.Boekingsstatus != 'Geannuleerd'
            AND v.Vluchtstatus != 'Geannuleerd'
            GROUP BY VluchtId
            ORDER BY AantalBoekingen DESC
            LIMIT 5;
        END
    ");

        DB::statement('
        CREATE PROCEDURE sp_PakFactuurBijId(
            IN p_FactuurId INT UNSIGNED
        )
        BEGIN
            SELECT 
                Id,
                PassagierId,
                Factuurdatum,
                TotaalBedrag AS Bedrag,
                Betaalmethode,
                Betaalstatus
            FROM Factuur
            WHERE Id = p_FactuurId;
        END
    ');

        DB::statement('
        CREATE PROCEDURE sp_WijzigFactuur(
            IN p_FactuurId INT,
            IN p_PassagierId INT,
            IN p_Factuurdatum DATE,
            IN p_TotaalBedrag DECIMAL(10, 2),
            IN p_Betaalmethode VARCHAR(50)
        )
        BEGIN
            UPDATE Factuur
            SET 
            PassagierId = p_PassagierId,
            Factuurdatum = p_Factuurdatum,
            TotaalBedrag = p_TotaalBedrag,
            Betaalmethode = p_Betaalmethode,
            Datumgewijzigd = NOW()
            WHERE Id = p_FactuurId;
        END
        ');

        DB::statement('
            CREATE PROCEDURE sp_PakAllePassagiers()
            BEGIN
                SELECT 
                    p.Id
                    ,p.PersoonId
                    ,per.Voornaam
                    ,per.Tussenvoegsel
                    ,per.Achternaam
                    ,p.Nummer
                    ,p.PassagierType
                    ,p.IsActief
                    ,p.Opmerking
                    ,p.Datumaangemaakt
                    ,p.Datumgewijzigd
                FROM Passagier p
                INNER JOIN Persoon per ON p.PersoonId = per.Id
                ORDER BY p.Nummer ASC;
            END
        ');

        DB::statement('
            CREATE PROCEDURE sp_AnnuleerFactuur(
                IN p_FactuurId INT
            )
            BEGIN
                DELETE FROM Factuur
                WHERE id = p_FactuurId;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
