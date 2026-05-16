<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVitalRequest;
use App\Models\Vital;

class VitalController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        $vitals  = Vital::where('patient_id', $patient->id)
            ->orderByDesc('recorded_at')
            ->paginate(20);

        // Chart data — last 14 readings
        $chartData = Vital::where('patient_id', $patient->id)
            ->orderByDesc('recorded_at')
            ->take(14)
            ->get()
            ->reverse()
            ->values();

        return view('patient.vitals.index', compact('vitals', 'chartData'));
    }

    public function create()
    {
        return view('patient.vitals.create');
    }

    public function store(StoreVitalRequest $request)
    {
        $patient = auth()->user()->patient;
        Vital::create(array_merge($request->validated(), ['patient_id' => $patient->id]));

        return redirect()->route('patient.vitals.index')
            ->with('success', 'Vital signs recorded successfully.');
    }

    public function destroy(Vital $vital)
    {
        if ($vital->patient_id !== auth()->user()->patient?->id) {
            abort(403);
        }
        $vital->delete();

        return back()->with('success', 'Entry deleted.');
    }
}
