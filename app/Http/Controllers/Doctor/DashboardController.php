<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;

        $stats = [
            'total_appointments'   => Appointment::where('doctor_id', $doctor->id)->count(),
            'pending_appointments' => Appointment::where('doctor_id', $doctor->id)->where('status', 'pending')->count(),
            'today_appointments'   => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())->count(),
            'completed'            => Appointment::where('doctor_id', $doctor->id)->where('status', 'completed')->count(),
        ];

        $upcoming = Appointment::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->whereDate('appointment_date', '>=', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        $pending = Appointment::with('patient.user')
            ->where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact('stats', 'upcoming', 'pending', 'doctor'));
    }
}
