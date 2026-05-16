<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\LabOrder;

class MedicalRecordController extends Controller
{
    public function index()
    {
        $patient      = auth()->user()->patient;
        $records      = MedicalRecord::where('patient_id', $patient->id)
            ->with('doctor.user')
            ->orderByDesc('record_date')
            ->paginate(15);
        $prescriptions = Prescription::where('patient_id', $patient->id)
            ->with('doctor.user', 'items')
            ->orderByDesc('prescription_date')
            ->paginate(10);
        $labOrders     = LabOrder::where('patient_id', $patient->id)
            ->with('doctor.user', 'results')
            ->orderByDesc('order_date')
            ->paginate(10);

        return view('patient.medical_records.index', compact('records', 'prescriptions', 'labOrders'));
    }

    public function show(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->patient_id !== auth()->user()->patient?->id) {
            abort(403);
        }
        $medicalRecord->load(['doctor.user', 'prescriptions']);
        return view('patient.medical_records.show', compact('medicalRecord'));
    }

    public function downloadPdf(MedicalRecord $medicalRecord)
    {
        if ($medicalRecord->patient_id !== auth()->user()->patient?->id) {
            abort(403);
        }
        
        $medicalRecord->load(['doctor.user', 'patient.user', 'prescriptions']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.prescription', compact('medicalRecord'));
        
        return $pdf->download('Prescription-' . $medicalRecord->id . '.pdf');
    }
}
