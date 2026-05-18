<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $patient = auth()->user()->patient;
        $query   = Appointment::with('doctor.user', 'invoice.payments')->where('patient_id', $patient->id);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(10);
        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = Doctor::with('user')->where('status', 'active')->get();
        return view('patient.appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id'        => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason'           => 'required|string|max:500',
        ]);

        $patient = auth()->user()->patient;

        $appointment = Appointment::create([
            'doctor_id'        => $validated['doctor_id'],
            'patient_id'       => $patient->id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'reason'           => $validated['reason'],
            'status'           => 'pending',
        ]);

        // AUTOMATICALLY send WhatsApp confirmation that appointment is requested
        try {
            $name = $appointment->patient->user->name;
            $doctorName = $appointment->doctor->user->name;
            $date = \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y');
            $time = $appointment->appointment_time;
            $msg = "Hello $name, your appointment request with Dr. $doctorName on $date at $time has been received and is pending approval. We will notify you once approved!";
            \App\Services\WhatsappService::send($appointment->patient->user->phone ?? '9999999999', $msg);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send booking WhatsApp: " . $e->getMessage());
        }

        return redirect()->route('patient.appointments.index')->with('success', 'Appointment booked successfully! Waiting for doctor approval.');
    }

    public function destroy(Appointment $appointment)
    {
        if ($appointment->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Appointment cancelled.');
    }
}
