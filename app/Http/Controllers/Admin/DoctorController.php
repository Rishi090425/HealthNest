<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = Doctor::with('user');
        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'));
        }
        $doctors = $query->latest()->paginate(10);
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users',
            'password'         => 'required|min:8',
            'phone'            => 'nullable|string|max:20',
            'specialization'   => 'required|string|max:255',
            'qualification'    => 'required|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'license_number'   => 'nullable|string|max:100',
            'bio'              => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
        ]);

        // Strip any "Dr.", "Dr ", "Doctor " prefix from the doctor name to keep it clean in DB
        $cleanName = preg_replace('/^(Dr\.?|Doctor)\s+/i', '', $validated['name']);

        $user = User::create([
            'name'     => $cleanName,
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'doctor',
            'phone'    => $validated['phone'] ?? null,
        ]);

        Doctor::create([
            'user_id'          => $user->id,
            'specialization'   => $validated['specialization'],
            'qualification'    => $validated['qualification'],
            'experience_years' => $validated['experience_years'] ?? 0,
            'license_number'   => $validated['license_number'] ?? null,
            'bio'              => $validated['bio'] ?? null,
            'consultation_fee' => $validated['consultation_fee'] ?? 0,
        ]);

        // Send Credentials Email
        $emailSent = true;
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(
                new \App\Mail\DoctorCredentialsMail($user->name, $user->email, $validated['password'])
            );
        } catch (\Exception $e) {
            $emailSent = false;
            \Illuminate\Support\Facades\Log::error('Email failed: ' . $e->getMessage());
        }

        // Send Credentials via WhatsApp
        try {
            $loginUrl = url('/login');
            $docDisplayName = preg_match('/^(Dr\.?|Doctor)\s+/i', $user->name) ? $user->name : "Dr. " . $user->name;
            $msg = "Hello {$docDisplayName}, your professional account at Health Nest has been successfully created. You can log in using: Link: {$loginUrl} | Email: {$user->email} | Password: {$validated['password']}";
            \App\Services\WhatsappService::send($user->phone ?? '9999999999', $msg);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('WhatsApp credentials failed: ' . $e->getMessage());
        }

        $successMsg = $emailSent 
            ? 'Doctor added successfully and credentials sent to email & WhatsApp.'
            : 'Doctor added successfully (WhatsApp sent, but Email failed to deliver — please check mail configuration).';

        return redirect()->route('admin.doctors.index')->with('success', $successMsg);
    }

    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:users,email,' . $doctor->user_id,
            'phone'            => 'nullable|string|max:20',
            'specialization'   => 'required|string|max:255',
            'qualification'    => 'required|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'license_number'   => 'nullable|string|max:100',
            'bio'              => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'status'           => 'required|in:active,inactive',
        ]);

        // Strip any "Dr.", "Dr ", "Doctor " prefix from the doctor name to keep it clean in DB
        $cleanName = preg_replace('/^(Dr\.?|Doctor)\s+/i', '', $validated['name']);

        $doctor->user->update([
            'name'   => $cleanName,
            'email'  => $validated['email'],
            'phone'  => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        $doctor->update([
            'specialization'   => $validated['specialization'],
            'qualification'    => $validated['qualification'],
            'experience_years' => $validated['experience_years'] ?? 0,
            'license_number'   => $validated['license_number'] ?? null,
            'bio'              => $validated['bio'] ?? null,
            'consultation_fee' => $validated['consultation_fee'] ?? 0,
            'status'           => $validated['status'],
        ]);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}
