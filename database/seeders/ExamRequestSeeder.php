<?php

namespace Database\Seeders;

use App\Models\ExamRequest;
use Illuminate\Database\Seeder;

class ExamRequestSeeder extends Seeder
{
    public function run(): void
    {
        ExamRequest::factory()->count(3)->create();
    }
}