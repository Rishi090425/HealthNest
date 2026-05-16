<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Vaccination;
use Illuminate\Http\Request;

class VaccinationController extends Controller
{
    public function index()
    {
        $vaccinations = auth()->user()->patient->vaccinations()->orderBy('date_received', 'desc')->get();
        return view('patient.vaccinations.index', compact('vaccinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vaccine_name'  => 'required|string|max:255',
            'date_received' => 'required|date',
            'provider'      => 'nullable|string|max:255',
            'next_due_date' => 'nullable|date|after:date_received',
            'notes'         => 'nullable|string',
        ]);

        auth()->user()->patient->vaccinations()->create($validated);

        return back()->with('success', 'Vaccination log added.');
    }

    public function destroy(Vaccination $vaccination)
    {
        if ($vaccination->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }
        $vaccination->delete();
        return back()->with('success', 'Vaccination record deleted.');
    }
}
