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

        if (session('hide_table_data', false)) {
            $payments = [];
        }

        return view('payment.index', compact('payments'));
    }

    public function create()
    {
        return view('payment.create');
    }

    public function store(Request $request) 
    {

    }
}
