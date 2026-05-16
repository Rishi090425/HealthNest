<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = auth()->user()->doctor->leaveRequests()->latest()->get();
        return view('doctor.leaves.index', compact('leaves'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:500',
        ]);

        auth()->user()->doctor->leaveRequests()->create($validated + ['status' => 'pending']);

        return back()->with('success', 'Leave request submitted.');
    }

    public function destroy(LeaveRequest $leave)
    {
        if ($leave->doctor_id !== auth()->user()->doctor->id || $leave->status !== 'pending') {
            abort(403);
        }
        $leave->delete();
        return back()->with('success', 'Leave request cancelled.');
    }
}
