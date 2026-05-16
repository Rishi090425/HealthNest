<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function create(Appointment $appointment)
    {
        // Ensure doctor owns this appointment
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }
        $consultation = $appointment->consultation;
        return view('doctor.consultations.create', compact('appointment', 'consultation'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== auth()->user()->doctor->id) {
            abort(403);
        }

        $validated = $request->validate([
            'diagnosis'        => 'required|string',
            'treatment'        => 'required|string',
            'notes'            => 'nullable|string',
            'follow_up_date'   => 'nullable|date|after:today',
            'prescription_file'=> 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('prescription_file')) {
            $filePath = $request->file('prescription_file')->store('prescriptions', 'public');
        }

        Consultation::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'diagnosis'         => $validated['diagnosis'],
                'treatment'         => $validated['treatment'],
                'notes'             => $validated['notes'] ?? null,
                'follow_up_date'    => $validated['follow_up_date'] ?? null,
                'prescription_file' => $filePath ?? $appointment->consultation?->prescription_file,
            ]
        );

        $appointment->update(['status' => 'completed']);

        return redirect()->route('doctor.appointments.index')->with('success', 'Consultation notes saved.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load('consultation', 'patient.user');
        return view('doctor.consultations.show', compact('appointment'));
    }
}
