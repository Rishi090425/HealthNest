<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['doctor.user', 'patient.user']);

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient.user', fn($q2) => $q2->where('name', 'like', "%$search%"))
                  ->orWhereHas('doctor.user', fn($q2) => $q2->where('name', 'like', "%$search%"));
            });
        }

        $appointments = $query->latest()->paginate(15);

        $counts = [
            'all'       => Appointment::count(),
            'pending'   => Appointment::where('status', 'pending')->count(),
            'approved'  => Appointment::where('status', 'approved')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'counts'));
    }

    /**
     * Admin approves a pending appointment.
     * Only the assigned doctor can mark it as completed.
     */
    public function approve(Appointment $appointment)
    {
        if (!in_array($appointment->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Only pending or cancelled appointments can be approved.');
        }
        $appointment->update(['status' => 'approved']);
        return back()->with('success', "Appointment for {$appointment->patient->user->name} approved.");
    }

    /**
     * Admin rejects/cancels an appointment.
     */
    public function reject(Appointment $appointment)
    {
        if ($appointment->status === 'completed') {
            return back()->with('error', 'Completed appointments cannot be cancelled.');
        }
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Appointment rejected/cancelled.');
    }

    /** Generic status update — kept for edge cases */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate(['status' => 'required|in:pending,approved,cancelled']);
        $appointment->update(['status' => $request->status]);
        return back()->with('success', 'Appointment status updated.');
    }
}
