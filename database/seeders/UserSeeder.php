<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rijschool.nl',
            'password' => 'achraf123',
            'rolename' => 'admin',
        ]);

        // instructor account
        User::create([
            'name' => 'Instructor User',
            'email' => 'instructeur@rijschool.nl',
            'password' => 'achraf123',
            'rolename' => 'instructor',
        ]);

        // Student account
        User::create([
            'name' => 'Student User',
            'email' => 'leerling@rijschool.nl',
            'password' => 'achraf123',
            'rolename' => 'student',
        ]);

        User::factory()->count(5)->create();
    }
}
