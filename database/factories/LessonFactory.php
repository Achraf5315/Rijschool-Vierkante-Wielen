<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Instructor;
use App\Models\Lesson;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $startTime = fake()->dateTimeBetween('now', '+30 days');
        $endTime = (clone $startTime)->modify('+1 hour');

        return [
            'ClientId' => Client::factory(),
            'InstructorId' => Instructor::factory(),
            'VehicleId' => Vehicle::factory(),
            'ClientPackageId' => null,
            'StartTime' => $startTime,
            'EndTime' => $endTime,
            'Location' => fake()->city(),
            'Status' => fake()->randomElement(['Open', 'Planned', 'Confirmed', 'InProgress', 'Completed', 'CancelledByClient', 'CancelledByInstructor']),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}