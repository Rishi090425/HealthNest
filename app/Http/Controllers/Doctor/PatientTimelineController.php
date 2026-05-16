<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\SoapNote;
use App\Models\Prescription;
use App\Models\LabOrder;
use App\Models\Vital;
use App\Models\MedicalRecord;
use App\Models\Referral;

class PatientTimelineController extends Controller
{
    public function show(Patient $patient)
    {
        $doctor = auth()->user()->doctor;

        // Ensure doctor has treated this patient
        $treated = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->exists();

        if (!$treated) {
            abort(403, 'You have not treated this patient.');
        }

        $patient->load('user');

        $timeline = collect();

        // Appointments
        Appointment::where('patient_id', $patient->id)
            ->with('doctor.user')
            ->get()
            ->each(fn($a) => $timeline->push([
                'date' => $a->appointment_date,
                'type' => 'appointment',
                'data' => $a,
            ]));

        // SOAP Notes
        SoapNote::where('patient_id', $patient->id)->get()
            ->each(fn($n) => $timeline->push(['date' => $n->created_at, 'type' => 'soap', 'data' => $n]));

        // Prescriptions
        Prescription::where('patient_id', $patient->id)->with('items')->get()
            ->each(fn($p) => $timeline->push(['date' => $p->prescription_date, 'type' => 'prescription', 'data' => $p]));

        // Lab Orders
        LabOrder::where('patient_id', $patient->id)->with('results')->get()
            ->each(fn($l) => $timeline->push(['date' => $l->order_date, 'type' => 'lab', 'data' => $l]));

        // Medical Records
        MedicalRecord::where('patient_id', $patient->id)->get()
            ->each(fn($r) => $timeline->push(['date' => $r->record_date, 'type' => 'record', 'data' => $r]));

        $timeline = $timeline->sortByDesc('date')->values();
        $vitals   = Vital::where('patient_id', $patient->id)->orderByDesc('recorded_at')->take(10)->get();

        return view('doctor.patients.timeline', compact('patient', 'timeline', 'vitals'));
    }
}
