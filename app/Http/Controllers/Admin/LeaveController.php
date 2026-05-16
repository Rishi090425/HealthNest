<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = LeaveRequest::with('doctor.user')->latest()->paginate(15);
        return view('admin.leaves.index', compact('leaves'));
    }

    public function update(Request $request, LeaveRequest $leave)
    {
        $request->validate([
            'status'      => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string|max:255',
        ]);

        $leave->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Leave request ' . $request->status . '.');
    }
}
