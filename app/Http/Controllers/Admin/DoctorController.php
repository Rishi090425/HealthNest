<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Mail\DoctorCredentialsMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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

        // Send credentials email
        $successMsg = 'Doctor added successfully!';
        
        try {
            Log::info("===== DOCTOR CREDENTIALS EMAIL =====");
            Log::info("Attempting to send credentials to: " . $user->email);
            Log::info("Mail Driver: " . config('mail.default'));
            Log::info("SMTP Host: " . config('mail.mailers.smtp.host'));
            Log::info("From Address: " . config('mail.from.address'));
            
            // DEBUG: send synchronously so we can see the real SMTP/transport error immediately.
            // After debugging, you can switch back to ->queue(...).
            Mail::to($user->email)->send(new DoctorCredentialsMail($user->display_name, $user->email, $validated['password']));

            Log::info("✓ Email sent to: " . $user->email);
            $successMsg .= ' Credentials have been sent to ' . $user->email . '.';
            
            
        } catch (\Exception $e) {
            Log::error('✗ Email Failed: ' . $e->getMessage(), [
                'exception' => $e,
                'recipient' => $user->email,
                'mail_default' => config('mail.default'),
                'queue_default' => config('queue.default'),
                'smtp_host' => config('mail.mailers.smtp.host'),
                'smtp_port' => config('mail.mailers.smtp.port'),
                'smtp_encryption' => config('mail.mailers.smtp.encryption'),
                'from_address' => config('mail.from.address'),
            ]);

            Log::error('Exception Class: ' . get_class($e));
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Stack Trace: ' . $e->getTraceAsString());

            $successMsg .= ' (⚠️  Email sending failed - Check logs. Manual credentials: Email: ' . $user->email . ' | Password: ' . $validated['password'] . ')';
        }

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
