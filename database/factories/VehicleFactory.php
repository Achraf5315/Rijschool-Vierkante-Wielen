<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'LicensePlate' => fake()->unique()->bothify('??-###-??'),
            'Brand' => fake()->company(),
            'Model' => fake()->word(),
            'Year' => fake()->numberBetween(2015, 2026),
            'Transmission' => fake()->randomElement(['Manual', 'Automatic']),
            'Category' => 'B',
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
            'APKExpiry' => fake()->dateTimeBetween('+3 months', '+2 years')->format('Y-m-d'),
            'InsuranceExpiry' => fake()->dateTimeBetween('+3 months', '+2 years')->format('Y-m-d'),
        ];
    }
}