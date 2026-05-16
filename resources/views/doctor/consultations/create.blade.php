@extends('layouts.app')
@section('title', 'Add Consultation Notes')
@section('page-title', 'Consultation Notes')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8">
        <!-- Patient Info -->
        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-lg font-bold text-blue-600">
                {{ strtoupper(substr($appointment->patient->user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $appointment->patient->user->name }}</h2>
                <p class="text-sm text-gray-500">
                    {{ $appointment->appointment_date->format('d M Y') }} at {{ $appointment->appointment_time }}
                    @if($appointment->reason) · {{ $appointment->reason }}@endif
                </p>
            </div>
            <span class="ml-auto text-xs font-medium px-2.5 py-1 rounded-full capitalize {{ $appointment->status_badge }}">{{ $appointment->status }}</span>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('doctor.consultations.store', $appointment) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis *</label>
                <textarea name="diagnosis" rows="3" required
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    placeholder="Describe the diagnosis...">{{ old('diagnosis', $consultation?->diagnosis) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Treatment Plan *</label>
                <textarea name="treatment" rows="3" required
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    placeholder="Recommended treatment...">{{ old('treatment', $consultation?->treatment) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                <textarea name="notes" rows="2"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    placeholder="Any additional notes...">{{ old('notes', $consultation?->notes) }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Date</label>
                    <input type="date" name="follow_up_date" value="{{ old('follow_up_date', $consultation?->follow_up_date?->format('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Prescription File</label>
                    <input type="file" name="prescription_file" accept=".pdf,.jpg,.jpeg,.png"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @if($consultation?->prescription_file)
                        <p class="text-xs text-green-600 mt-1"><i class="fas fa-paperclip mr-1"></i>File already uploaded</p>
                    @endif
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Consultation & Complete
                </button>
                <a href="{{ route('doctor.appointments.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
