<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            \App\Services\AuditLogService::log('Login', 'User logged into the system.');
            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:doctor,patient',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'phone'    => $validated['phone'] ?? null,
        ]);

        // Create corresponding profile
        if ($user->role === 'doctor') {
            Doctor::create(['user_id' => $user->id, 'specialization' => 'General', 'qualification' => 'MBBS']);
        } elseif ($user->role === 'patient') {
            Patient::create(['user_id' => $user->id]);
        }

        Auth::login($user);
        \App\Services\AuditLogService::log('Register', 'User created an account as ' . $user->role);
        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        \App\Services\AuditLogService::log('Logout', 'User logged out.');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectByRole(User $user)
    {
        return match($user->role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'doctor'  => redirect()->route('doctor.dashboard'),
            'patient' => redirect()->route('patient.dashboard'),
            default   => redirect('/'),
        };
    }
}
