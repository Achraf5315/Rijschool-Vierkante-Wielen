<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\DrivingLesson;
use App\Models\Instructor;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DrivingLessonSeeder extends Seeder
{
    /**
     * Seed a few sample driving lessons.
     */
    public function run(): void
    {
        $client = Client::query()->where('IsActive', true)->orderBy('Id')->firstOrFail();
        $instructor = Instructor::query()->where('IsActive', true)->orderBy('Id')->firstOrFail();
        $vehicle = Vehicle::query()->where('IsActive', true)->orderBy('Id')->firstOrFail();

        $lessons = [
            [
                'start' => Carbon::parse('2026-06-09 09:00:00'),
                'end' => Carbon::parse('2026-06-09 10:00:00'),
                'location' => 'Centrum Utrecht',
                'status' => 'Planned',
                'notes' => 'Oefenen op kruispunten en bochten.',
            ],
            [
                'start' => Carbon::parse('2026-06-10 13:00:00'),
                'end' => Carbon::parse('2026-06-10 14:00:00'),
                'location' => 'Rondje buitengebied',
                'status' => 'Confirmed',
                'notes' => 'Snelweg- en invoegtraining.',
            ],
            [
                'start' => Carbon::parse('2026-06-11 16:30:00'),
                'end' => Carbon::parse('2026-06-11 17:30:00'),
                'location' => 'Leslocatie aan Schoolweg',
                'status' => 'Open',
                'notes' => 'Laatste check voor de les.',
            ],
        ];

        foreach ($lessons as $lesson) {
            DrivingLesson::updateOrCreate(
                [
                    'ClientId' => $client->Id,
                    'InstructorId' => $instructor->Id,
                    'VehicleId' => $vehicle->Id,
                    'StartTime' => $lesson['start']->toDateTimeString(),
                ],
                [
                    'ClientPackageId' => null,
                    'EndTime' => $lesson['end']->toDateTimeString(),
                    'Location' => $lesson['location'],
                    'Status' => $lesson['status'],
                    'IsActive' => true,
                    'Notes' => $lesson['notes'],
                ]
            );
        }
    }
}