<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->search, fn($q) => $q->where(function($sq) use ($request) {
                $sq->where('name', 'like', "%{$request->search}%")
                   ->orWhere('email', 'like', "%{$request->search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = ['admin', 'doctor', 'patient'];
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,doctor,patient',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
            'status'   => 'active',
        ]);

        if ($user->role === 'doctor') {
            Doctor::create([
                'user_id'        => $user->id,
                'specialization' => $request->specialization ?? 'General',
                'qualification'  => $request->qualification ?? 'MBBS',
                'status'         => 'active',
            ]);
        } elseif ($user->role === 'patient') {
            Patient::create(['user_id' => $user->id]);
        }

        AuditLog::record('Created User', 'User', $user->id);

        return redirect()->route('admin.users.index')
            ->with('success', "Account for {$user->name} created successfully.");
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'doctor', 'patient'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'role'   => 'required|in:admin,doctor,patient',
            'phone'  => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'role'   => $request->role,
            'phone'  => $request->phone,
            'status' => $request->status,
        ]);

        if ($request->password) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        AuditLog::record('Updated User', 'User', $user->id);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account.');
        }

        $user->update(['status' => 'inactive']);
        AuditLog::record('Deactivated User', 'User', $user->id);

        return back()->with('success', 'User deactivated successfully.');
    }
}
