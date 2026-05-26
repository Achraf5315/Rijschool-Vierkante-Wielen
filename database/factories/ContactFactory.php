<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'FirstName' => fake()->firstName(),
            'LastName' => fake()->lastName(),
            'Phone' => fake()->numerify('06########'),
            'DateOfBirth' => fake()->date('Y-m-d', '-18 years'),
            'AddressId' => Address::factory(),
            'UserId' => User::factory(),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}