<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // De demo-accounts worden meteen als geverifieerd aangemaakt, zodat ze
        // direct toegang hebben tot het dashboard (de 'verified' middleware).
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rijschool.nl',
            'password' => 'achraf123',
            'rolename' => 'admin',
            'email_verified_at' => now(),
        ]);

        // instructor account
        User::create([
            'name' => 'Instructor User',
            'email' => 'instructeur@rijschool.nl',
            'password' => 'achraf123',
            'rolename' => 'instructor',
            'email_verified_at' => now(),
        ]);

        // Student account
        User::create([
            'name' => 'Student User',
            'email' => 'leerling@rijschool.nl',
            'password' => 'achraf123',
            'rolename' => 'student',
            'email_verified_at' => now(),
        ]);

        User::factory()->count(5)->create();
    }
}
