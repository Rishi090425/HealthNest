<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Review;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', '30');

        $from = now()->subDays((int) $period);

        // Revenue by day for chart
        $dailyRevenue = Payment::selectRaw('DATE(payment_date) as date, SUM(amount) as total')
            ->where('payment_date', '>=', $from)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Appointments by status
        $appointmentStats = Appointment::selectRaw('status, COUNT(*) as count')
            ->where('created_at', '>=', $from)
            ->groupBy('status')
            ->pluck('count', 'status');

        // Top doctors by appointment count
        $topDoctors = Doctor::withCount(['appointments' => fn($q) => $q->where('created_at', '>=', $from)])
            ->with('user')
            ->orderByDesc('appointments_count')
            ->take(5)
            ->get();

        // Patient registrations by day
        $newPatients = Patient::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', $from)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue       = Payment::where('payment_date', '>=', $from)->sum('amount');
        $totalAppointments  = Appointment::where('created_at', '>=', $from)->count();
        $averageRating      = Review::where('created_at', '>=', $from)->avg('rating');

        return view('admin.reports.index', compact(
            'dailyRevenue', 'appointmentStats', 'topDoctors',
            'newPatients', 'totalRevenue', 'totalAppointments',
            'averageRating', 'period'
        ));
    }
}
