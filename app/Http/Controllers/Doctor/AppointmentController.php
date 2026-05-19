<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth()->user()->doctor;
        $query  = Appointment::with('patient.user', 'invoice')->where('doctor_id', $doctor->id);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date) {
            $query->whereDate('appointment_date', $request->date);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                               ->orderBy('appointment_time', 'desc')
                               ->paginate(10);

        $counts = [
            'all'       => Appointment::where('doctor_id', $doctor->id)->count(),
            'pending'   => Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'approved'  => Appointment::where('doctor_id', $doctor->id)->where('status', 'approved')->count(),
            'completed' => Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count(),
            'cancelled' => Appointment::where('doctor_id', $doctor->id)->where('status', 'cancelled')->count(),
        ];

        return view('doctor.appointments.index', compact('appointments', 'counts'));
    }

    /** Approve a pending appointment */
    public function approve(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        $appointment->update(['status' => 'approved']);

        // AUTOMATICALLY send WhatsApp message to the patient when appointment is approved!
        try {
            \App\Services\WhatsappService::sendAppointmentReminder($appointment);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to automatically send approval WhatsApp: " . $e->getMessage());
        }

        return back()->with('success', "Appointment for {$appointment->patient->user->name} approved.");
    }

    /** Reject / cancel an appointment */
    public function reject(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Appointment cancelled.');
    }

    /**
     * Mark an approved appointment as completed and request payment.
     */
    public function complete(Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);

        if ($appointment->status !== 'approved') {
            return back()->with('error', 'Only approved appointments can be marked as completed.');
        }

        $appointment->update(['status' => 'completed']);

        // Generate Invoice automatically if it doesn't exist
        $doctor = auth()->user()->doctor;
        $invoice = \App\Models\Invoice::firstOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'patient_id' => $appointment->patient_id,
                'amount'     => $doctor->consultation_fee ?? 500,
                'status'     => 'unpaid',
                'issued_date' => now(),
                'due_date'    => now()->addDays(7),
            ]
        );

        // AUTOMATICALLY send WhatsApp invoice notification!
        try {
            $name = $appointment->patient->user->name;
            $doctorName = $appointment->doctor->user->name;
            $docDisplayName = preg_match('/^(Dr\.?|Doctor)\s+/i', $doctorName) ? $doctorName : "Dr. " . $doctorName;
            $amount = $invoice->amount;
            $msg = "Hello $name, your appointment with $docDisplayName has been marked as completed. An invoice of ₹$amount has been generated. Please complete your payment at: " . route('patient.appointments.index');
            \App\Services\WhatsappService::send($appointment->patient->user->phone ?? '9999999999', $msg);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send completed WhatsApp: " . $e->getMessage());
        }

        return back()->with('success', "Appointment marked as completed and Payment Request sent to Admin.");
    }

    /** Generic status update (kept for internal use) */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $this->authorizeDoctor($appointment);
        $request->validate(['status' => 'required|in:pending,approved,completed,cancelled']);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Appointment status updated.');
    }

    private function authorizeDoctor(Appointment $appointment): void
    {
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403, 'You are not authorized to manage this appointment.');
        }
    }
}
