<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorAvailability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $doctor       = auth()->user()->doctor;
        $daysOrder    = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

        $availability = DoctorAvailability::where('doctor_id', $doctor->id)
            ->get()
            ->sortBy(fn($a) => array_search($a->day_of_week, $daysOrder))
            ->keyBy('day_of_week');

        $days = $daysOrder;
        return view('doctor.availability.index', compact('availability', 'days', 'doctor'));
    }

    public function update(Request $request)
    {
        $doctor = auth()->user()->doctor;
        $days   = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

        foreach ($days as $day) {
            $isAvailable = isset($request->available[$day]);
            if ($isAvailable) {
                $request->validate([
                    "start_time.$day" => 'required',
                    "end_time.$day"   => 'required',
                ]);
            }

            DoctorAvailability::updateOrCreate(
                ['doctor_id' => $doctor->id, 'day_of_week' => $day],
                [
                    'start_time'   => $request->start_time[$day] ?? '09:00',
                    'end_time'     => $request->end_time[$day] ?? '17:00',
                    'is_available' => $isAvailable,
                ]
            );
        }

        return back()->with('success', 'Availability schedule updated.');
    }
}
