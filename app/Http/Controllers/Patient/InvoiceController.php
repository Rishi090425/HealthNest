<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $patient  = auth()->user()->patient;
        $invoices = Invoice::where('patient_id', $patient->id)
            ->with('appointment.doctor.user', 'payments')
            ->orderByDesc('issued_date')
            ->paginate(15);

        return view('patient.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        if ($invoice->patient_id !== auth()->user()->patient?->id) {
            abort(403);
        }
        $invoice->load('payments', 'appointment.doctor.user');
        return view('patient.invoices.show', compact('invoice'));
    }

    public function pay(Request $request, Invoice $invoice)
    {
        if ($invoice->patient_id !== auth()->user()->patient?->id) {
            abort(403);
        }

        $request->validate([
            'payment_method'  => 'required|in:cash,card,upi,net_banking,cash_at_hospital,pay_later',
            'amount'          => 'required_unless:payment_method,pay_later|numeric|min:0',
        ]);

        if ($request->payment_method === 'pay_later') {
            return back()->with('success', 'Your request to pay later has been recorded. Please complete the payment within 7 days.');
        }

        if ($request->payment_method === 'cash_at_hospital') {
            $invoice->update(['status' => 'pending']); // Reset to pending/waiting for hospital
            return back()->with('success', 'Please proceed to the hospital reception to complete your cash payment.');
        }

        Payment::create([
            'invoice_id'     => $invoice->id,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'payment_date'   => now(),
        ]);

        // Refresh invoice balance check
        $invoice->refresh();
        if ($invoice->balance <= 0) {
            $invoice->update(['status' => 'paid']);
        } else {
            $invoice->update(['status' => 'partial']);
        }

        return back()->with('success', 'Payment of ₹' . number_format($request->amount, 2) . ' recorded successfully.');
    }
}
