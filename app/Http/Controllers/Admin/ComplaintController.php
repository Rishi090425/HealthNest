<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with('patient.user')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->type) {
            $query->where('type', $request->type);
        }

        $complaints = $query->paginate(15);
        return view('admin.complaints.index', compact('complaints'));
    }

    public function respond(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:2000',
            'status'         => 'required|in:pending,in_review,resolved',
        ]);

        $complaint->update([
            'admin_response' => $validated['admin_response'],
            'status'         => $validated['status'],
            'resolved_at'    => $validated['status'] === 'resolved' ? now() : null,
        ]);

        return back()->with('success', 'Response saved successfully.');
    }
}
