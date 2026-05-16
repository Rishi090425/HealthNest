<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $patient    = auth()->user()->patient;
        $complaints = Complaint::where('patient_id', $patient->id)
            ->latest()
            ->paginate(10);
        return view('patient.complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('patient.complaints.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'type'    => 'required|in:complaint,change_request,feedback',
            'message' => 'required|string|max:2000',
        ]);

        $patient = auth()->user()->patient;

        Complaint::create([
            'patient_id' => $patient->id,
            'subject'    => $validated['subject'],
            'type'       => $validated['type'],
            'message'    => $validated['message'],
            'status'     => 'pending',
        ]);

        return redirect()->route('patient.complaints.index')
            ->with('success', 'Your request has been submitted. We will respond within 24-48 hours.');
    }
}
