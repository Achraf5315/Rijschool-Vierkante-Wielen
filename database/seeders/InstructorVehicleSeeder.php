<?php

namespace Database\Seeders;

use App\Models\InstructorVehicle;
use Illuminate\Database\Seeder;

class InstructorVehicleSeeder extends Seeder
{
    public function run(): void
    {
        InstructorVehicle::factory()->count(4)->create();
    }
}