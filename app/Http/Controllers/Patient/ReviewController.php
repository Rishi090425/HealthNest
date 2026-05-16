<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Review;
use App\Models\Appointment;

class ReviewController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        $reviews = Review::where('patient_id', $patient->id)
            ->with('doctor.user', 'appointment')
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('patient.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $patient = auth()->user()->patient;
        $appointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->whereDoesntHave('review')
            ->with('doctor.user')
            ->get();
        return view('patient.reviews.create', compact('appointments'));
    }

    public function store(StoreReviewRequest $request)
    {
        $patient = auth()->user()->patient;
        $exists  = Review::where('patient_id', $patient->id)
            ->where('appointment_id', $request->appointment_id)
            ->exists();
        if ($exists) {
            return back()->with('error', 'Already reviewed this appointment.');
        }
        Review::create(array_merge($request->validated(), ['patient_id' => $patient->id]));
        return redirect()->route('patient.reviews.index')
            ->with('success', 'Thank you for your review!');
    }
}
