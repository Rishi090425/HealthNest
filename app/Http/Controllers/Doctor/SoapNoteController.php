<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSoapNoteRequest;
use App\Models\SoapNote;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\AuditLog;

class SoapNoteController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        $notes  = SoapNote::with('patient.user', 'appointment')
            ->where('doctor_id', $doctor->id)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('doctor.soap_notes.index', compact('notes'));
    }

    public function create()
    {
        $doctor   = auth()->user()->doctor;
        $patients = Patient::with('user')
            ->whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))
            ->get();
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->whereDoesntHave('soapNote')
            ->with('patient.user')
            ->get();

        return view('doctor.soap_notes.create', compact('patients', 'appointments'));
    }

    public function store(StoreSoapNoteRequest $request)
    {
        $doctor = auth()->user()->doctor;
        $note   = SoapNote::create(array_merge(
            $request->validated(),
            ['doctor_id' => $doctor->id]
        ));

        AuditLog::record('Created SOAP Note', 'SoapNote', $note->id);

        return redirect()->route('doctor.soap-notes.show', $note)
            ->with('success', 'SOAP note saved successfully.');
    }

    public function show(SoapNote $soapNote)
    {
        $this->authorizeDoctor($soapNote);
        $soapNote->load('patient.user', 'appointment', 'doctor.user');

        return view('doctor.soap_notes.show', compact('soapNote'));
    }

    public function edit(SoapNote $soapNote)
    {
        $this->authorizeDoctor($soapNote);
        $doctor   = auth()->user()->doctor;
        $patients = Patient::with('user')
            ->whereHas('appointments', fn($q) => $q->where('doctor_id', $doctor->id))
            ->get();

        return view('doctor.soap_notes.edit', compact('soapNote', 'patients'));
    }

    public function update(StoreSoapNoteRequest $request, SoapNote $soapNote)
    {
        $this->authorizeDoctor($soapNote);
        $soapNote->update($request->validated());
        AuditLog::record('Updated SOAP Note', 'SoapNote', $soapNote->id);

        return redirect()->route('doctor.soap-notes.show', $soapNote)
            ->with('success', 'SOAP note updated.');
    }

    public function destroy(SoapNote $soapNote)
    {
        $this->authorizeDoctor($soapNote);
        $soapNote->delete();
        AuditLog::record('Deleted SOAP Note', 'SoapNote', $soapNote->id);

        return redirect()->route('doctor.soap-notes.index')
            ->with('success', 'SOAP note deleted.');
    }

    private function authorizeDoctor(SoapNote $note): void
    {
        if ($note->doctor_id !== auth()->user()->doctor?->id) {
            abort(403);
        }
    }
}
