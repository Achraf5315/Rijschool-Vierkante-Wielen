<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

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
        $invoices = DB::table('Invoice as i')
            ->join('Client as c', 'i.ClientId', '=', 'c.Id')
            ->join('Contact as co', 'c.ContactId', '=', 'co.Id')
            ->where('i.IsActive', true)
            ->whereIn('i.Status', ['Draft', 'Sent', 'Overdue'])
            ->orderBy('i.InvoiceNumber')
            ->get([
                'i.Id',
                'i.ClientId',
                'i.InvoiceNumber',
                'i.TotalAmount',
                'i.Status',
                DB::raw("CONCAT_WS(' ', co.FirstName, co.LastName) AS ClientName"),
            ]);

        return view('payment.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => ['required', 'integer', 'exists:Invoice,Id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['required', 'in:iDEAL,CreditCard,BankTransfer,Cash,Tikkie'],
            'transaction_ref' => ['required', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'invoice_id.required' => 'Selecteer een factuur.',
            'invoice_id.integer' => 'De geselecteerde factuur is ongeldig.',
            'invoice_id.exists' => 'De geselecteerde factuur bestaat niet.',
            'amount.required' => 'Vul een bedrag in.',
            'amount.numeric' => 'Het bedrag moet een getal zijn.',
            'amount.min' => 'Het bedrag moet minimaal 0,01 zijn.',
            'method.required' => 'Selecteer een betaalmethode.',
            'method.in' => 'Selecteer een geldige betaalmethode.',
            'transaction_ref.required' => 'Vul een transactiereferentie in.',
            'transaction_ref.string' => 'De transactiereferentie is ongeldig.',
            'transaction_ref.max' => 'De transactiereferentie mag maximaal 150 tekens bevatten.',
            'notes.string' => 'De opmerking is ongeldig.',
            'notes.max' => 'De opmerking mag maximaal 255 tekens bevatten.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validatie mislukt.',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            // Prevent duplicate payments: same transaction ref OR same invoice+amount already exists
            $invoiceId = $request->integer('invoice_id');
            $transactionRef = $request->input('transaction_ref');
            $amount = $request->input('amount');

            $duplicate = DB::table('Payment')
                ->where('IsActive', 1)
                ->where(function ($q) use ($invoiceId, $transactionRef, $amount) {
                    $q->where('TransactionRef', $transactionRef)
                        ->orWhere(function ($q2) use ($invoiceId, $amount) {
                            $q2->where('InvoiceId', $invoiceId)
                                ->where('Amount', $amount);
                        });
                })->exists();

            if ($duplicate) {
                return response()->json([
                    'message' => 'Er bestaat al een betaling voor deze factuur of transactie.',
                ], 409);
            }
            $result = DB::select(
                'CALL sp_CreatePayment(?, ?, ?, ?, ?)',
                [
                    $request->integer('invoice_id'),
                    $request->input('amount'),
                    $request->input('method'),
                    $request->input('transaction_ref'),
                    $request->input('notes'),
                ]
            );

            $paymentId = $result[0]->PaymentId ?? null;

            if (! $paymentId) {
                return response()->json([
                    'message' => 'Betaling aangemaakt, maar er is geen betalings-ID teruggegeven.',
                ], 500);
            }

            return response()->json([
                'message' => 'Betaling succesvol geregistreerd.',
                'betaling_id' => (int) $paymentId,
            ], 200);
        } catch (QueryException $exception) {
            $statusCode = str_contains($exception->getMessage(), 'Invoice not found') ? 400 : 500;

            return response()->json([
                'message' => $statusCode === 400
                    ? 'De geselecteerde factuur kon niet worden gevonden.'
                    : 'Er is een databasefout opgetreden bij het aanmaken van de betaling.',
            ], $statusCode);
        } catch (Throwable $exception) {
            $statusCode = str_contains($exception->getMessage(), 'Invoice not found') ? 400 : 500;

            return response()->json([
                'message' => $statusCode === 400
                    ? 'De geselecteerde factuur kon niet worden gevonden.'
                    : 'Er is een onverwachte fout opgetreden bij het aanmaken van de betaling.',
            ], $statusCode);
        }

    }
}
