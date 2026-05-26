<?php

namespace Database\Seeders;

use App\Models\DrivingPackage;
use Illuminate\Database\Seeder;

class DrivingPackageSeeder extends Seeder
{
    public function run(): void
    {
        DrivingPackage::factory()->count(4)->create();
    }
}