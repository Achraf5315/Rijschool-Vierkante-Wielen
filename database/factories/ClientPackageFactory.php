<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientPackage;
use App\Models\DrivingPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientPackageFactory extends Factory
{
    protected $model = ClientPackage::class;

    public function definition(): array
    {
        return [
            'ClientId' => Client::factory(),
            // Hergebruik een bestaand lesrijpakket als die er is (bij seeden), zodat
            // er geen extra willekeurige pakketten ontstaan. In tests (lege database)
            // valt het terug op het aanmaken van een pakket.
            'PackageId' => DrivingPackage::query()->inRandomOrder()->value('Id') ?? DrivingPackage::factory(),
            'LessonsUsed' => fake()->numberBetween(0, 5),
            'IsCompleted' => false,
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
            'PurchasedAt' => now(),
        ];
    }
}