<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Instructor;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 50, 750);
        $vatRate = 21.00;
        $vatAmount = round($subtotal * ($vatRate / 100), 2);

        return [
            'InvoiceNumber' => 'FCT-' . fake()->unique()->numberBetween(100000, 999999),
            'ClientId' => Client::factory(),
            'InstructorId' => Instructor::factory(),
            'IssueDate' => now()->toDateString(),
            'DueDate' => now()->addDays(14)->toDateString(),
            'Subtotal' => $subtotal,
            'VATRate' => $vatRate,
            'VATAmount' => $vatAmount,
            'TotalAmount' => round($subtotal + $vatAmount, 2),
            'Status' => fake()->randomElement(['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled']),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
        ];
    }
}