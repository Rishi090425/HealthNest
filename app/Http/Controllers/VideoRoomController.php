<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class VideoRoomController extends Controller
{
    public function show(Appointment $appointment)
    {
        // Security: only doctor or patient of this appointment can join
        $user = auth()->user();
        if ($user->isDoctor() && $appointment->doctor_id !== $user->doctor->id) abort(403);
        if ($user->isPatient() && $appointment->patient_id !== $user->patient->id) abort(403);

        if (!$appointment->video_room_id) {
            return back()->with('error', 'Video room not generated for this appointment.');
        }

        return view('video_room', compact('appointment'));
    }

    public function generate(Appointment $appointment)
    {
        if (auth()->user()->isDoctor() && $appointment->doctor_id === auth()->user()->doctor->id) {
            $appointment->update([
                'video_room_id' => bin2hex(random_bytes(10))
            ]);
            return back()->with('success', 'Video consultation link generated.');
        }
        abort(403);
    }
}
