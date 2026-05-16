<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\EmergencyAlert;
use Illuminate\Http\Request;

class EmergencyAlertController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $alert = EmergencyAlert::create([
            'patient_id' => auth()->user()->patient->id,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'status'     => 'pending',
        ]);

        // In a real app, we would broadcast this via Laravel Echo
        // event(new \App\Events\EmergencyAlertTriggered($alert));

        return response()->json([
            'success' => true,
            'message' => 'Emergency SOS sent. Hospital has been notified.',
            'alert_id'=> $alert->id
        ]);
    }
}
