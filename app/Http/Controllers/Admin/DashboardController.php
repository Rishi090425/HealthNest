<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_patients'     => Patient::count(),
            'total_doctors'      => Doctor::count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'approved_appointments' => Appointment::where('status', 'approved')->count(),
        ];

        $recent_appointments = Appointment::with(['doctor.user', 'patient.user'])
            ->latest()
            ->take(5)
            ->get();

        $recent_patients = Patient::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_appointments', 'recent_patients'));
    }
}
