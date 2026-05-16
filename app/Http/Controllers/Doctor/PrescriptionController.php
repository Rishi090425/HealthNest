<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        $doctor        = auth()->user()->doctor;
        $prescriptions = Prescription::with('patient.user', 'items')
            ->where('doctor_id', $doctor->id)
            ->orderByDesc('prescription_date')
            ->paginate(15);

        return view('doctor.prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $doctor = auth()->user()->doctor;
        $patients = Patient::with('user')
            ->whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))
            ->get();
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->with('patient.user')
            ->get();

        return view('doctor.prescriptions.create', compact('patients', 'appointments'));
    }

    public function store(StorePrescriptionRequest $request)
    {
        $doctor = auth()->user()->doctor;
        $data   = $request->validated();
        $items  = $data['items'];
        unset($data['items']);

        $prescription = Prescription::create(array_merge($data, ['doctor_id' => $doctor->id]));

        foreach ($items as $item) {
            $prescription->items()->create($item);
        }

        AuditLog::record('Issued Prescription', 'Prescription', $prescription->id);

        return redirect()->route('doctor.prescriptions.show', $prescription)
            ->with('success', 'Prescription issued successfully.');
    }

    public function show(Prescription $prescription)
    {
        $this->authorizeDoctor($prescription);
        $prescription->load('patient.user', 'items', 'appointment');

        return view('doctor.prescriptions.show', compact('prescription'));
    }

    public function destroy(Prescription $prescription)
    {
        $this->authorizeDoctor($prescription);
        $prescription->delete();
        AuditLog::record('Deleted Prescription', 'Prescription', $prescription->id);

        return redirect()->route('doctor.prescriptions.index')
            ->with('success', 'Prescription deleted.');
    }

    private function authorizeDoctor(Prescription $p): void
    {
        if ($p->doctor_id !== auth()->user()->doctor?->id) {
            abort(403);
        }
    }
}
