<?php

namespace Database\Factories;

use App\Models\DrivingPackage;
use Illuminate\Database\Eloquent\Factories\Factory;

class DrivingPackageFactory extends Factory
{
    protected $model = DrivingPackage::class;

    public function definition(): array
    {
        return [
            'Name' => fake()->words(3, true),
            'Description' => fake()->sentence(),
            'LessonCount' => fake()->numberBetween(5, 30),
            'LessonDuration' => 60,
            'Price' => fake()->randomFloat(2, 199, 2500),
            'Category' => 'B',
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}