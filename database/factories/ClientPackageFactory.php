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
            'PackageId' => DrivingPackage::factory(),
            'LessonsUsed' => fake()->numberBetween(0, 5),
            'IsCompleted' => false,
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
            'PurchasedAt' => now(),
        ];
    }
}