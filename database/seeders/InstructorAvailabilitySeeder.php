<?php

namespace Database\Seeders;

use App\Models\InstructorAvailability;
use Illuminate\Database\Seeder;

class InstructorAvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        InstructorAvailability::factory()->count(5)->create();
    }
}