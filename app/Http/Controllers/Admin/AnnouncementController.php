<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Branch;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with('branch')->latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('admin.announcements.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'target_role'      => 'required|in:all,doctor,patient',
            'target_branch_id' => 'nullable|exists:branches,id',
        ]);

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement published successfully.');
    }

    public function edit(Announcement $announcement)
    {
        $branches = Branch::all();
        return view('admin.announcements.edit', compact('announcement', 'branches'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'target_role'      => 'required|in:all,doctor,patient',
            'target_branch_id' => 'nullable|exists:branches,id',
        ]);

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return back()->with('success', 'Announcement deleted.');
    }
}
