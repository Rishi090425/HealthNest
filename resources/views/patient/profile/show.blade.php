@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center text-2xl font-bold text-purple-600">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
                    <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                        @if($patient->gender)<span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full capitalize">{{ $patient->gender }}</span>@endif
                        @if($patient->blood_group)<span class="text-xs bg-red-50 text-red-600 font-bold px-2 py-1 rounded-full">{{ $patient->blood_group }}</span>@endif
                        @if($patient->age)<span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded-full">{{ $patient->age }} years old</span>@endif
                    </div>
                </div>
            </div>
            <a href="{{ route('patient.profile.edit') }}" class="text-sm text-blue-600 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition-colors font-medium">
                <i class="fas fa-edit mr-1"></i>Edit
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-5 pt-5 border-t border-gray-100">
            <div><p class="text-xs text-gray-500 mb-1">Phone</p><p class="text-sm font-medium text-gray-800">{{ auth()->user()->phone ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Date of Birth</p><p class="text-sm font-medium text-gray-800">{{ $patient->date_of_birth?->format('d M Y') ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Blood Group</p><p class="text-sm font-medium text-gray-800">{{ $patient->blood_group ?? '—' }}</p></div>
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
                <p class="text-xs text-gray-500 mb-2">Medical History</p>
                <p class="text-sm text-gray-800 bg-yellow-50 rounded-lg p-4">{{ $patient->medical_history }}</p>
            </div>
        @endif
    </div>
</div>
@endsection
