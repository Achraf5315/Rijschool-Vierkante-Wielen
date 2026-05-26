<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'Street' => fake()->streetName(),
            'HouseNumber' => fake()->buildingNumber(),
            'PostalCode' => fake()->numerify('######'),
            'City' => fake()->city(),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}