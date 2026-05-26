<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ContactSeeder::class);

        $studentContact = Contact::where('FirstName', 'Wesley')
            ->where('LastName', 'Leerling')
            ->firstOrFail();

        Client::updateOrCreate(
            ['BSN' => '123456789'],
            [
                'ContactId' => $studentContact->Id,
                'LicenseCategory' => 'B',
                'IsActive' => true,
                'Notes' => 'Seeded student client',
            ]
        );
    }
}
