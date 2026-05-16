<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\WalkInQueue;
use App\Models\Department;
use Illuminate\Http\Request;

class WalkInQueueController extends Controller
{
    public function index()
    {
        $patient = auth()->user()->patient;
        $activeQueue = WalkInQueue::with('department')
            ->where('patient_id', $patient->id)
            ->whereIn('status', ['waiting', 'calling'])
            ->first();

        $departments = Department::all();

        return view('patient.queue.index', compact('activeQueue', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $patient = auth()->user()->patient;

        // Check if already in queue
        $exists = WalkInQueue::where('patient_id', $patient->id)
            ->whereIn('status', ['waiting', 'calling'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'You are already in a queue.');
        }

        // Get next queue number for today in this department
        $lastNum = WalkInQueue::where('department_id', $request->department_id)
            ->whereDate('created_at', today())
            ->max('queue_number') ?? 0;

        $queue = WalkInQueue::create([
            'patient_id'    => $patient->id,
            'department_id' => $request->department_id,
            'queue_number'  => $lastNum + 1,
            'status'        => 'waiting',
            'estimated_wait_time' => ($lastNum) * 15, // Simple logic: 15 mins per person
        ]);

        return redirect()->route('patient.queue.index')->with('success', 'Joined the queue successfully!');
    }

    public function cancel(WalkInQueue $queue)
    {
        if ($queue->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        $queue->update(['status' => 'cancelled']);

        return redirect()->route('patient.queue.index')->with('success', 'Left the queue.');
    }
}
