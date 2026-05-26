<?php

namespace Database\Factories;

use App\Models\Instructor;
use App\Models\InstructorAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorAvailabilityFactory extends Factory
{
    protected $model = InstructorAvailability::class;

    public function definition(): array
    {
        return [
            'InstructorId' => Instructor::factory(),
            'DayOfWeek' => fake()->numberBetween(1, 7),
            'StartTime' => fake()->time('H:i:s'),
            'EndTime' => fake()->time('H:i:s'),
            'IsRecurring' => true,
            'SpecificDate' => now()->toDateString(),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}