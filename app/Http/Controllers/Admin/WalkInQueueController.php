<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalkInQueue;
use App\Models\Department;
use Illuminate\Http\Request;

class WalkInQueueController extends Controller
{
    public function index()
    {
        $queues = WalkInQueue::with(['patient.user', 'department'])
            ->whereDate('created_at', today())
            ->whereIn('status', ['waiting', 'calling'])
            ->orderBy('queue_number')
            ->get()
            ->groupBy('department_id');

        $departments = Department::all();

        return view('admin.queue.index', compact('queues', 'departments'));
    }

    public function callNext(Request $request)
    {
        $request->validate(['department_id' => 'required|exists:departments,id']);

        // Mark current calling in this dept as completed (auto-cleanup)
        WalkInQueue::where('department_id', $request->department_id)
            ->where('status', 'calling')
            ->update(['status' => 'completed']);

        // Get next waiting
        $next = WalkInQueue::where('department_id', $request->department_id)
            ->where('status', 'waiting')
            ->whereDate('created_at', today())
            ->orderBy('queue_number')
            ->first();

        if ($next) {
            $next->update(['status' => 'calling']);
            return back()->with('success', 'Patient #' . $next->queue_number . ' is being called.');
        }

        return back()->with('error', 'No patients in waiting for this department.');
    }

    public function updateStatus(Request $request, WalkInQueue $queue)
    {
        $request->validate(['status' => 'required|in:waiting,calling,completed,cancelled']);
        $queue->update(['status' => $request->status]);
        return back()->with('success', 'Queue status updated.');
    }
}
