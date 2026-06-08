<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model: DrivingPackage (Lesrijpakket)
 *
 * Vertegenwoordigt een lesrijpakket dat een administrator aanmaakt en dat
 * gastgebruikers kunnen bekijken in het overzicht. Een pakket bevat o.a. een
 * naam, omschrijving, het aantal lessen, de duur per les en de prijs.
 *
 * De tabel volgt de bestaande projectconventie: PascalCase tabel- en
 * kolomnamen, een `Id` primary key en `CreatedAt` / `UpdatedAt` timestamps.
 */
class DrivingPackage extends Model
{
    use HasFactory;

    /** De databasetabel die bij dit model hoort. */
    protected $table = 'DrivingPackage';

    /** De naam van de primary key kolom (afwijkend van de standaard `id`). */
    protected $primaryKey = 'Id';

    /** De primary key is een auto-increment integer. */
    public $incrementing = true;

    /** Het type van de primary key. */
    protected $keyType = 'int';

    /** Laravel beheert de timestamps automatisch. */
    public $timestamps = true;

    /** Afwijkende kolomnaam voor het aanmaak-tijdstip. */
    const CREATED_AT = 'CreatedAt';

    /** Afwijkende kolomnaam voor het wijzig-tijdstip. */
    const UPDATED_AT = 'UpdatedAt';

    /**
     * Velden die NIET massaal toewijsbaar zijn. Alleen `Id` is beschermd zodat
     * de overige (door de administrator ingevulde) velden via het formulier
     * kunnen worden opgeslagen.
     */
    protected $guarded = ['Id'];

    /**
     * Cast attributen naar het juiste PHP-type, zodat we in views en logica
     * met echte getallen/booleans werken in plaats van strings.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'Price' => 'decimal:2',
            'LessonCount' => 'integer',
            'LessonDuration' => 'integer',
            'IsActive' => 'boolean',
        ];
    }

    /**
     * Query scope: alleen de actieve pakketten.
     *
     * Gastgebruikers zien in het overzicht uitsluitend pakketten die door een
     * administrator op actief zijn gezet. Gebruik: DrivingPackage::active()->get()
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('IsActive', true);
    }
}
