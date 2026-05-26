<?php

namespace Database\Factories;

use App\Models\DrivingPackage;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceLineFactory extends Factory
{
    protected $model = InvoiceLine::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 4);
        $unitPrice = fake()->randomFloat(2, 25, 250);

        return [
            'InvoiceId' => Invoice::factory(),
            'Description' => fake()->sentence(),
            'Quantity' => $quantity,
            'UnitPrice' => $unitPrice,
            'LineTotal' => round($quantity * $unitPrice, 2),
            'LessonId' => Lesson::factory(),
            'PackageId' => DrivingPackage::factory(),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}