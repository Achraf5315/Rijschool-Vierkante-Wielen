<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Instructor;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ContactSeeder::class);

        $instructorContact = Contact::where('FirstName', 'Luuk')
            ->where('LastName', 'Instructeur')
            ->firstOrFail();

        Instructor::updateOrCreate(
            ['LicenseNumber' => 'RN-00001-LU'],
            [
                'ContactId' => $instructorContact->Id,
                'Certification' => 'Rijinstructeur',
                'MaxStudentsPerDay' => 8,
                'HourlyRate' => 55.00,
                'IsActive' => true,
                'Notes' => 'Seeded instructor account',
            ]
        );
    }
}
