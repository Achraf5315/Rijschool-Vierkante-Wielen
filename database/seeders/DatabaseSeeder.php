<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ClientPackageSeeder;
use Database\Seeders\ClientSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\DrivingPackageSeeder;
use Database\Seeders\DrivingLessonSeeder;
use Database\Seeders\ExamRequestSeeder;
use Database\Seeders\InstructorAvailabilitySeeder;
use Database\Seeders\InstructorSeeder;
use Database\Seeders\InstructorVehicleSeeder;
use Database\Seeders\InvoiceLineSeeder;
use Database\Seeders\InvoiceSeeder;
use Database\Seeders\NotificationSeeder;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VehicleSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AddressSeeder::class,
            ContactSeeder::class,
            ClientSeeder::class,
            InstructorSeeder::class,
            VehicleSeeder::class,
            InstructorVehicleSeeder::class,
            DrivingPackageSeeder::class,
            ClientPackageSeeder::class,
            DrivingLessonSeeder::class,
            InvoiceSeeder::class,
            InvoiceLineSeeder::class,
            PaymentSeeder::class,
            NotificationSeeder::class,
            ExamRequestSeeder::class,
            InstructorAvailabilitySeeder::class,
        ]);

    }
}
