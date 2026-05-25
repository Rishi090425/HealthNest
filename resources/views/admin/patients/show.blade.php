@extends('layouts.app')
@section('title', 'Patient Profile')
@section('page-title', 'Patient Profile')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Profile Card -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center text-2xl font-bold text-purple-600">
                {{ strtoupper(substr($patient->user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $patient->user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $patient->user->email }}</p>
                <div class="flex items-center gap-3 mt-2 flex-wrap">
                    @if($patient->gender)<span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full capitalize">{{ $patient->gender }}</span>@endif
                    @if($patient->blood_group)<span class="text-xs bg-red-50 text-red-600 font-bold px-2 py-1 rounded-full">{{ $patient->blood_group }}</span>@endif
                    @if($patient->age)<span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-full">{{ $patient->age }} yrs</span>@endif
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100">
            <div><p class="text-xs text-gray-500 mb-1">Phone</p><p class="text-sm font-medium text-gray-800">{{ $patient->user->phone ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Date of Birth</p><p class="text-sm font-medium text-gray-800">{{ $patient->date_of_birth?->format('d M Y') ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Emergency Contact</p><p class="text-sm font-medium text-gray-800">{{ $patient->emergency_contact_name ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Emergency Phone</p><p class="text-sm font-medium text-gray-800">{{ $patient->emergency_contact_phone ?? '—' }}</p></div>
        </div>
        @if($patient->address)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Address</p>
            <p class="text-sm text-gray-800">{{ $patient->address }}</p>
        </div>
        @endif
        @if($patient->medical_history)
        <div class="mt-4 pt-4 border-t border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Medical History</p>
            <p class="text-sm text-gray-800">{{ $patient->medical_history }}</p>
        </div>
        @endif
    </div>
    <!-- Appointments -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-semibold text-gray-800 mb-4">Appointment History</h3>
        @forelse($patient->appointments as $appt)
        <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
            <div class="text-center min-w-12">
                <div class="text-lg font-bold text-gray-800">{{ $appt->appointment_date->format('d') }}</div>
                <div class="text-xs text-gray-500">{{ $appt->appointment_date->format('M Y') }}</div>
            </div>
            <div class="flex-1">
                <div class="text-sm font-medium text-gray-800">{{ $appt->doctor->user->display_name }}</div>
                <div class="text-xs text-gray-500">{{ $appt->doctor->specialization }} · {{ $appt->appointment_time }}</div>
                @if($appt->reason)<div class="text-xs text-gray-400 mt-0.5 truncate">{{ $appt->reason }}</div>@endif
            </div>
            <span class="text-xs font-medium px-2.5 py-1 rounded-full capitalize {{ $appt->status_badge }}">{{ $appt->status }}</span>
        </div>
        @empty
            <p class="text-gray-400 text-sm text-center py-6">No appointments found.</p>
        @endforelse
    </div>
    <a href="{{ route('admin.patients.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-800">
        <i class="fas fa-arrow-left"></i> Back to Patients
    </a>
</div>
@endsection
