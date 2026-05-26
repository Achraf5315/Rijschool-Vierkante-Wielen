<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\InstructorVehicle;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorVehicleFactory extends Factory
{
    protected $model = InstructorVehicle::class;

    public function definition(): array
    {
        return [
            'InstructorId' => Instructor::factory(),
            'VehicleId' => Vehicle::factory(),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
            'AssignedAt' => now(),
        ];
    }
}