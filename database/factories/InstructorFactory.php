<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorFactory extends Factory
{
    protected $model = Instructor::class;

    public function definition(): array
    {
        return [
            'ContactId' => Contact::factory(),
            'LicenseNumber' => fake()->unique()->bothify('RN-#####-??'),
            'Certification' => fake()->randomElement(['Rijinstructeur', 'Senior instructeur', 'Mentorinstructeur']),
            'MaxStudentsPerDay' => fake()->numberBetween(4, 8),
            'HourlyRate' => fake()->randomFloat(2, 35, 85),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}