<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');
        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'));
        }
        $patients = $query->latest()->paginate(10);
        return view('admin.patients.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        $patient->load('user', 'appointments.doctor.user', 'appointments.consultation');
        return view('admin.patients.show', compact('patient'));
    }

    public function destroy(Patient $patient)
    {
        $patient->user->delete();
        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted successfully.');
    }
}
