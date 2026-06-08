<?php

namespace Database\Seeders;

use App\Models\DrivingPackage;
use Illuminate\Database\Seeder;

class DrivingPackageSeeder extends Seeder
{
    /**
     * Maakt standaard 3 lesrijpakketten aan met realistische, leesbare teksten
     * (dus geen willekeurige "lorem ipsum"-tekst). Door updateOrCreate op de naam
     * te gebruiken ontstaan er geen dubbele pakketten als de seeder opnieuw draait.
     */
    public function run(): void
    {
        $packages = [
            [
                'Name' => 'Startpakket',
                'Description' => 'Ideaal om kennis te maken met autorijden. 10 rijlessen onder begeleiding van een vaste instructeur.',
                'LessonCount' => 10,
                'LessonDuration' => 60,
                'Price' => 549.00,
                'Category' => 'B',
            ],
            [
                'Name' => 'Standaardpakket',
                'Description' => 'Onze meest gekozen optie: 20 rijlessen waarmee de meeste leerlingen examenklaar zijn.',
                'LessonCount' => 20,
                'LessonDuration' => 60,
                'Price' => 1049.00,
                'Category' => 'B',
            ],
            [
                'Name' => 'Examenpakket',
                'Description' => 'Compleet pakket van 30 rijlessen inclusief extra examenvoorbereiding voor een grote slagingskans.',
                'LessonCount' => 30,
                'LessonDuration' => 60,
                'Price' => 1499.00,
                'Category' => 'B',
            ],
        ];

        foreach ($packages as $package) {
            DrivingPackage::updateOrCreate(
                ['Name' => $package['Name']],
                array_merge($package, ['IsActive' => true]),
            );
        }
    }
}
