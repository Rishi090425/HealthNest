<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;

        $stats = [
            'total_appointments'   => Appointment::where('patient_id', $patient->id)->count(),
            'upcoming_appointments'=> Appointment::where('patient_id', $patient->id)
                ->whereIn('status', ['pending', 'approved'])
                ->whereDate('appointment_date', '>=', today())
                ->count(),
            'completed_appointments'=> Appointment::where('patient_id', $patient->id)
                ->where('status', 'completed')->count(),
        ];

        $upcoming = Appointment::with('doctor.user')
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->take(5)
            ->get();

        $recent = Appointment::with(['doctor.user', 'consultation'])
            ->where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->latest()
            ->take(3)
            ->get();

        return view('patient.dashboard', compact('stats', 'upcoming', 'recent', 'patient'));
    }
}
