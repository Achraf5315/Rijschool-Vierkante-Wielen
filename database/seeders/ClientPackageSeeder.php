<?php

namespace Database\Seeders;

use App\Models\ClientPackage;
use Illuminate\Database\Seeder;

class ClientPackageSeeder extends Seeder
{
    public function run(): void
    {
        ClientPackage::factory()->count(4)->create();
    }
}