<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSpecialtyRequest;
use App\Models\Specialty;
use App\Models\Department;
use App\Models\AuditLog;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::with('department')->withCount('doctors')->paginate(20);
        return view('admin.specialties.index', compact('specialties'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('admin.specialties.create', compact('departments'));
    }

    public function store(StoreSpecialtyRequest $request)
    {
        $s = Specialty::create($request->validated());
        AuditLog::record('Created Specialty', 'Specialty', $s->id);
        return redirect()->route('admin.specialties.index')
            ->with('success', 'Specialty created.');
    }

    public function edit(Specialty $specialty)
    {
        $departments = Department::all();
        return view('admin.specialties.edit', compact('specialty', 'departments'));
    }

    public function update(StoreSpecialtyRequest $request, Specialty $specialty)
    {
        $specialty->update($request->validated());
        AuditLog::record('Updated Specialty', 'Specialty', $specialty->id);
        return redirect()->route('admin.specialties.index')
            ->with('success', 'Specialty updated.');
    }

    public function destroy(Specialty $specialty)
    {
        $specialty->delete();
        AuditLog::record('Deleted Specialty', 'Specialty', $specialty->id);
        return back()->with('success', 'Specialty deleted.');
    }
}
