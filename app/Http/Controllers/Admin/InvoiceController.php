<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['patient.user', 'appointment.doctor.user'])->latest()->paginate(10);
        $totalRevenue = Invoice::where('status', 'paid')->sum('amount');
        $pendingAmount = Invoice::where('status', '!=', 'paid')->sum('amount') - Payment::sum('amount');
        
        return view('admin.invoices.index', compact('invoices', 'totalRevenue', 'pendingAmount'));
    }

    public function create(Request $request)
    {
        $patients = Patient::with('user')->get();
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('status', 'completed')
            ->whereDoesntHave('invoice')
            ->get();
            
        $selected_appointment = $request->appointment_id ? Appointment::find($request->appointment_id) : null;

        return view('admin.invoices.create', compact('patients', 'appointments', 'selected_appointment'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'appointment_id' => 'nullable|exists:appointments,id|unique:invoices,appointment_id',
            'amount'         => 'required|numeric|min:0',
            'due_date'       => 'required|date|after_or_equal:today',
            'notes'          => 'nullable|string',
        ]);

        $invoice = Invoice::create(array_merge($validated, [
            'issued_date' => now(),
            'status'      => 'unpaid',
        ]));

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['patient.user', 'appointment.doctor.user', 'payments']);
        return view('admin.invoices.show', compact('invoice'));
    }
}
