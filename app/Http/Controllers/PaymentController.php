<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    private $payment;
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function index()
    {
        $payments = $this->payment->GetAllPayments();

        return view('payment.index', compact('payments'));
    }
}
