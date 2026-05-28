<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'InvoiceId' => Invoice::factory(),
            'Amount' => fake()->randomFloat(2, 25, 1000),
            'Method' => fake()->randomElement(['iDEAL', 'CreditCard', 'BankTransfer', 'Tikkie']),
            'TransactionRef' => fake()->bothify('TRX-##########'),
            'Status' => fake()->randomElement(['Ready to send', 'Pending', 'Completed', 'Failed', 'Refunded']),
            'IsActive' => true,
            'Notes' => fake()->optional()->sentence(),
            'PaymentDate' => now(),
        ];
    }
}