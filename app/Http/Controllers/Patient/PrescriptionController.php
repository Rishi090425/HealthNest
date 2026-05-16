<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        $prescriptions = Prescription::where('patient_id', $patient->id)
            ->with(['doctor.user', 'items'])
            ->latest('prescription_date')
            ->paginate(10);
            
        return view('patient.medical_records.index', compact('prescriptions')); // Or dedicated view
    }

    public function download(Prescription $prescription)
    {
        if ($prescription->patient_id !== auth()->user()->patient?->id) {
            abort(403);
        }

        $prescription->load(['doctor.user', 'patient.user', 'items']);

        $pdf = Pdf::loadView('pdf.prescription', compact('prescription'));
        
        return $pdf->download('Prescription-' . $prescription->id . '.pdf');
    }
}
