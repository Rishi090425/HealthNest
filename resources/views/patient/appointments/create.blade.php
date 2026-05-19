@extends('layouts.app')
@section('title', 'Book Appointment')
@section('page-title', 'Book Appointment')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-plus text-blue-600"></i>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">Book an Appointment</h2>
                <p class="text-sm text-gray-500">Fill in the details to schedule your visit</p>
            </div>
        </div>
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6">
                <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <form method="POST" action="{{ route('patient.appointments.store') }}" class="space-y-5">
            @csrf
            <!-- Doctor Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Doctor *</label>
                <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                    @foreach($doctors as $doctor)
                        <label class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 border-gray-100 hover:border-blue-200">
                            <input type="radio" name="doctor_id" value="{{ $doctor->id }}" class="text-blue-600 flex-shrink-0" {{ old('doctor_id') == $doctor->id ? 'checked' : '' }} required>
                            <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-sm font-bold text-blue-600 flex-shrink-0">
                                {{ strtoupper(substr($doctor->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-800">{{ $doctor->user->display_name }}</div>
                                <div class="text-xs text-gray-500">{{ $doctor->specialization }} · {{ $doctor->qualification }}</div>
                            </div>
                            <div class="text-sm font-semibold text-blue-600 flex-shrink-0">₹{{ number_format($doctor->consultation_fee) }}</div>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Appointment Date *</label>
                    <input type="date" name="appointment_date" value="{{ old('appointment_date') }}"
                        min="{{ today()->toDateString() }}" required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Time *</label>
                    <select name="appointment_time" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select time...</option>
                        @foreach(['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00'] as $time)
                            <option value="{{ $time }}" {{ old('appointment_time') === $time ? 'selected' : '' }}>{{ $time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Visit *</label>
                <textarea name="reason" rows="3" required maxlength="500"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                    placeholder="Describe your symptoms or reason for the visit...">{{ old('reason') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" id="book-btn" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg text-sm transition-colors">
                    <i class="fas fa-calendar-check mr-2"></i>Book Appointment
                </button>
                <a href="{{ route('patient.appointments.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
