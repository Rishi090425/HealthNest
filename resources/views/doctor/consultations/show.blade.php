@extends('layouts.app')
@section('title', 'Consultation Details')
@section('page-title', 'Consultation Details')
@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-lg font-bold text-blue-600">
                {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $appointment->patient->user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $appointment->appointment_date->format('d M Y') }} at {{ $appointment->appointment_time }}</p>
            </div>
            <span class="ml-auto text-xs font-medium px-2.5 py-1 rounded-full bg-green-100 text-green-800">Completed</span>
        </div>
        @if($appointment->consultation)
            <div class="space-y-5">
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Diagnosis</h3>
                    <p class="text-sm text-gray-800 bg-blue-50 rounded-lg p-4">{{ $appointment->consultation->diagnosis }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Treatment Plan</h3>
                    <p class="text-sm text-gray-800 bg-green-50 rounded-lg p-4">{{ $appointment->consultation->treatment }}</p>
                </div>
                @if($appointment->consultation->notes)
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Additional Notes</h3>
                    <p class="text-sm text-gray-800 bg-gray-50 rounded-lg p-4">{{ $appointment->consultation->notes }}</p>
                </div>
                @endif
                <div class="grid grid-cols-2 gap-4">
                    @if($appointment->consultation->follow_up_date)
                    <div class="bg-yellow-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Follow-up Date</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $appointment->consultation->follow_up_date->format('d M Y') }}</p>
                    </div>
                    @endif
                    @if($appointment->consultation->prescription_file)
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-xs text-gray-500 mb-1">Prescription</p>
                        <a href="{{ asset('storage/' . $appointment->consultation->prescription_file) }}" target="_blank"
                            class="text-sm font-semibold text-purple-600 hover:underline">
                            <i class="fas fa-download mr-1"></i>Download File
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        @else
            <p class="text-gray-400 text-sm text-center py-8">No consultation notes recorded.</p>
        @endif
    </div>
    <div class="flex gap-3">
        <a href="{{ route('doctor.consultations.create', $appointment) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors">
            <i class="fas fa-edit mr-2"></i>Edit Notes
        </a>
        <a href="{{ route('doctor.appointments.index') }}" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
    </div>
</div>
@endsection
