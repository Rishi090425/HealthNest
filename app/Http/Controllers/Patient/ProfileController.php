<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $patient = auth()->user()->patient;
        return view('patient.profile.show', compact('patient'));
    }

    public function edit()
    {
        $patient = auth()->user()->patient;
        return view('patient.profile.edit', compact('patient'));
    }

    public function update(Request $request)
    {
        $patient = auth()->user()->patient;

        $validated = $request->validate([
            'name'                    => 'required|string|max:255',
            'phone'                   => 'nullable|string|max:20',
            'date_of_birth'           => 'nullable|date',
            'gender'                  => 'nullable|in:male,female,other',
            'blood_group'             => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address'                 => 'nullable|string',
            'medical_history'         => 'nullable|string',
            'emergency_contact_name'  => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
        ]);

        auth()->user()->update([
            'name'  => $validated['name'],
            'phone' => $validated['phone'] ?? null,
        ]);

        $patient->update([
            'date_of_birth'           => $validated['date_of_birth'] ?? null,
            'gender'                  => $validated['gender'] ?? null,
            'blood_group'             => $validated['blood_group'] ?? null,
            'address'                 => $validated['address'] ?? null,
            'medical_history'         => $validated['medical_history'] ?? null,
            'emergency_contact_name'  => $validated['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
        ]);

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully.');
    }
}
