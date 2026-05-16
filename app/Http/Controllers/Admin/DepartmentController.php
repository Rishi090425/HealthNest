<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Models\Department;
use App\Models\AuditLog;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount('specialties', 'doctors')->paginate(20);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(StoreDepartmentRequest $request)
    {
        $dept = Department::create($request->validated());
        AuditLog::record('Created Department', 'Department', $dept->id);
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created.');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(StoreDepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());
        AuditLog::record('Updated Department', 'Department', $department->id);
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        AuditLog::record('Deleted Department', 'Department', $department->id);
        return back()->with('success', 'Department deleted.');
    }
}
