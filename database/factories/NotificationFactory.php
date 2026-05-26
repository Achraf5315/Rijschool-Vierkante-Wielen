<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        return [
            'UserId' => User::factory(),
            'Type' => fake()->randomElement([
                'LessonReminder',
                'LessonConfirmed',
                'LessonCancelled',
                'InvoiceGenerated',
                'PaymentReceived',
                'SystemMessage',
                'PackageLowBalance',
            ]),
            'Title' => fake()->sentence(3),
            'Message' => fake()->sentence(),
            'IsRead' => false,
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
            'SentAt' => now(),
            'ReadAt' => null,
        ];
    }
}