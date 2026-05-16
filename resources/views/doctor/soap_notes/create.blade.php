@extends('layouts.app')
@section('title', 'New SOAP Note')
@section('page-title', 'New SOAP Note')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
        <form method="POST" action="{{ route('doctor.soap-notes.store') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Patient</label>
                    <select name="patient_id" required class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">Select Patient...</option>
                        @foreach($patients as $p)
                        <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>{{ $p->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Appointment <span class="text-gray-400 text-xs">(optional)</span></label>
                    <select name="appointment_id" class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none">
                        <option value="">None</option>
                        @foreach($appointments as $appt)
                        <option value="{{ $appt->id }}" {{ old('appointment_id') == $appt->id ? 'selected' : '' }}>
                            {{ $appt->patient->user->name }} — {{ $appt->appointment_date->format('d M Y') }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            @foreach(['subjective' => 'Subjective (Patient\'s Chief Complaint)', 'objective' => 'Objective (Clinical Findings)', 'assessment' => 'Assessment (Diagnosis)', 'plan' => 'Plan (Treatment Plan)'] as $field => $label)
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
                <textarea name="{{ $field }}" rows="3" placeholder="Enter {{ $label }}..."
                    class="w-full rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:outline-none resize-none">{{ old($field) }}</textarea>
                @error($field) <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            @endforeach

            <div class="flex gap-3 mt-6">
                <a href="{{ route('doctor.soap-notes.index') }}" class="flex-1 text-center py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Cancel</a>
                <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-semibold py-2.5 rounded-xl transition-colors text-sm">
                    <i class="fas fa-save mr-2"></i>Save SOAP Note
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
