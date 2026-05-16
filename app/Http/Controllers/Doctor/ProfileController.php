<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $doctor = auth()->user()->doctor;
        return view('doctor.profile.index', compact('doctor'));
    }

    public function update(Request $request)
    {
        $doctor = auth()->user()->doctor;

        $validated = $request->validate([
            'specialization'   => 'required|string|max:255',
            'qualification'    => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0|max:60',
            'consultation_fee' => 'required|numeric|min:0|max:99999',
            'bio'              => 'nullable|string|max:1000',
            'phone'            => 'nullable|string|max:20',
        ]);

        $doctor->update([
            'specialization'   => $validated['specialization'],
            'qualification'    => $validated['qualification'],
            'experience_years' => $validated['experience_years'],
            'consultation_fee' => $validated['consultation_fee'],
            'bio'              => $validated['bio'] ?? null,
        ]);

        auth()->user()->update(['phone' => $validated['phone'] ?? null]);

        return back()->with('success', 'Profile and fee updated successfully.');
    }
}
