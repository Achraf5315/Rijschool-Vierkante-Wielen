<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        Address::updateOrCreate(
            [
                'Street' => 'Korenstraat',
                'HouseNumber' => '12',
                'PostalCode' => '1234AB',
                'City' => 'Utrecht',
            ],
            [
                'IsActive' => true,
                'Notes' => 'Admin contact address',
            ]
        );

        Address::updateOrCreate(
            [
                'Street' => 'Schoolweg',
                'HouseNumber' => '8',
                'PostalCode' => '2345BC',
                'City' => 'Utrecht',
            ],
            [
                'IsActive' => true,
                'Notes' => 'Instructor contact address',
            ]
        );

        Address::updateOrCreate(
            [
                'Street' => 'Opleidingslaan',
                'HouseNumber' => '24',
                'PostalCode' => '3456CD',
                'City' => 'Utrecht',
            ],
            [
                'IsActive' => true,
                'Notes' => 'Student contact address',
            ]
        );
    }
}
