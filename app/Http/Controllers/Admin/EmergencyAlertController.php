<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmergencyAlert;
use Illuminate\Http\Request;

class EmergencyAlertController extends Controller
{
    public function index()
    {
        $alerts = EmergencyAlert::with('patient.user')->latest()->paginate(15);
        return view('admin.emergency_alerts.index', compact('alerts'));
    }

    public function respond(EmergencyAlert $alert)
    {
        $alert->update([
            'status' => 'responding',
            'responder_id' => auth()->id(),
            'responded_at' => now(),
        ]);

        return back()->with('success', 'You have marked yourself as responding to this alert.');
    }

    public function resolve(EmergencyAlert $alert)
    {
        $alert->update([
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Alert resolved.');
    }
}
