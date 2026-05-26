<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ExamRequest;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamRequestFactory extends Factory
{
    protected $model = ExamRequest::class;

    public function definition(): array
    {
        return [
            'ClientId' => Client::factory(),
            'InstructorId' => Instructor::factory(),
            'RequestedDate' => now()->toDateString(),
            'ExamDate' => now()->addWeeks(4)->toDateString(),
            'ExamLocation' => fake()->city(),
            'ExamType' => fake()->randomElement(['Theory', 'Practical']),
            'Status' => fake()->randomElement(['Requested', 'Scheduled', 'Passed', 'Failed', 'Withdrawn']),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}