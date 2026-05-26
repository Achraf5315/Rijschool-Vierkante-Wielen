<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@rijschool.nl'],
            [
                'name' => 'Admin User',
                'password' => 'achraf123',
                'rolename' => 'admin',
            ]
        );
        $instructor = User::firstOrCreate(
            ['email' => 'instructeur@rijschool.nl'],
            [
                'name' => 'Instructor User',
                'password' => 'achraf123',
                'rolename' => 'instructor',
            ]
        );
        $student = User::firstOrCreate(
            ['email' => 'leerling@rijschool.nl'],
            [
                'name' => 'Student User',
                'password' => 'achraf123',
                'rolename' => 'student',
            ]
        );

        $adminAddress = Address::updateOrCreate(
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
        $instructorAddress = Address::updateOrCreate(
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
        $studentAddress = Address::updateOrCreate(
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

        Contact::updateOrCreate(
            ['FirstName' => 'Achraf', 'LastName' => 'Admin'],
            [
                'Phone' => '0600000001',
                'DateOfBirth' => '1985-01-01',
                'AddressId' => $adminAddress->Id,
                'UserId' => $admin->id,
                'IsActive' => true,
                'Notes' => 'Administrator contact',
            ]
        );

        Contact::updateOrCreate(
            ['FirstName' => 'Luuk', 'LastName' => 'Instructeur'],
            [
                'Phone' => '0600000002',
                'DateOfBirth' => '1990-02-02',
                'AddressId' => $instructorAddress->Id,
                'UserId' => $instructor->id,
                'IsActive' => true,
                'Notes' => 'Instructor contact',
            ]
        );

        Contact::updateOrCreate(
            ['FirstName' => 'Wesley', 'LastName' => 'Leerling'],
            [
                'Phone' => '0600000003',
                'DateOfBirth' => '2001-03-03',
                'AddressId' => $studentAddress->Id,
                'UserId' => $student->id,
                'IsActive' => true,
                'Notes' => 'Student contact',
            ]
        );
    }
}
